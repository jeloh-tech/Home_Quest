<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Listing;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Show the landlord dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get real statistics
        $totalProperties = Listing::where('user_id', $user->id)->count();
        $activeListings = Listing::where('user_id', $user->id)->where('status', 'active')->count();
        $rentedProperties = Listing::where('user_id', $user->id)->where('status', 'rented')->count();
        $pendingApplications = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        // Get unread messages count
        $unreadMessages = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Get recent activity (last 5 activities)
        $recentActivities = collect();

        // Recent applications
        $recentApplications = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['tenant', 'listing'])
        ->latest()
        ->take(3)
        ->get()
        ->map(function ($app) {
            return [
                'type' => 'application',
                'message' => "New application for {$app->listing->title} from {$app->tenant->name}",
                'date' => $app->created_at,
                'icon' => 'application'
            ];
        });

        // Recent payments
        $recentPayments = \App\Models\Payment::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['tenant', 'listing'])
        ->where('status', 'completed')
        ->latest()
        ->take(2)
        ->get()
        ->map(function ($payment) {
            return [
                'type' => 'payment',
                'message' => "Payment received: ₱" . number_format($payment->amount, 2) . " from {$payment->tenant->name}",
                'date' => $payment->payment_date,
                'icon' => 'payment'
            ];
        });

        // Recent issues
        $recentIssues = \App\Models\Issue::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['tenant', 'listing'])
        ->latest()
        ->take(2)
        ->get()
        ->map(function ($issue) {
            return [
                'type' => 'issue',
                'message' => "New maintenance request for {$issue->listing->title}: {$issue->title}",
                'date' => $issue->created_at,
                'icon' => 'issue'
            ];
        });

        // Combine and sort all activities
        $recentActivities = $recentApplications
            ->concat($recentPayments)
            ->concat($recentIssues)
            ->sortByDesc('date')
            ->take(5);

        // Get recent properties for the table
        $recentProperties = Listing::where('user_id', $user->id)
            ->withCount(['rentalApplications', 'rentalApplications as pending_applications_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Calculate monthly revenue
        $monthlyRevenue = \App\Models\Payment::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'completed')
        ->whereMonth('payment_date', now()->month)
        ->whereYear('payment_date', now()->year)
        ->sum('amount');

        return view('landlord.dashboard', compact(
            'totalProperties',
            'activeListings',
            'rentedProperties',
            'pendingApplications',
            'unreadMessages',
            'recentActivities',
            'recentProperties',
            'monthlyRevenue'
        ));
    }

    /**
     * Show the landlord profile page.
     */
    public function profile(): View
    {
        $user = Auth::user();

        // Calculate profile completion percentage
        $completionFields = [
            'name', 'email', 'phone', 'bio', 'location',
            'company_name', 'license_number', 'profile_photo_path'
        ];

        $completedFields = 0;
        foreach ($completionFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }

        $profileCompletion = round(($completedFields / count($completionFields)) * 100);

        // Get basic stats
        $stats = [
            'total_properties' => Listing::where('user_id', $user->id)->count(),
            'monthly_revenue' => \App\Models\Payment::whereHas('listing', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount')
        ];

        return view('landlord.profile', compact('profileCompletion', 'stats'));
    }

    /**
     * Show the add post page.
     */
    public function addPost(): View
    {
        $user = Auth::user();
        $verificationDocuments = null;
        $documentType = null;

        if ($user && ($user->valid_id_path || $user->valid_id_back_path)) {
            // Build verification documents array with proper image URLs
            // Load documents regardless of verification status as long as paths exist
            $verificationDocuments = [];
            if ($user->valid_id_path) {
                $verificationDocuments['front'] = asset('storage/' . $user->valid_id_path);
            }
            if ($user->valid_id_back_path) {
                $verificationDocuments['back'] = asset('storage/' . $user->valid_id_back_path);
            }
            $documentType = $user->document_type;
        }

        return view('landlord.add-post', compact('verificationDocuments', 'documentType'));
    }

    /**
     * Store a new property listing.
     */
    public function storeListing(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:500',
            'property_type' => 'required|string|in:House,Boarding House,Apartment,Condo',
            'room_count' => 'nullable|integer|min:0',
            'bathroom_count' => 'nullable|integer|min:0',
            'description' => 'required|string|max:1000',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images' => 'nullable|array|max:4',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'amenities' => 'nullable|array',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();

        // Handle main image upload
        $mainImagePath = $request->file('main_image')->store('listings', 'public');

        // Handle additional images
        $additionalImages = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('listings', 'public');
                $additionalImages[] = $path;
            }
        }

        // Create the listing
        $listing = Listing::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'price' => $request->price,
            'location' => $request->location,
            'property_type' => $request->property_type,
            'room_count' => $request->room_count ?? 0,
            'bathroom_count' => $request->bathroom_count ?? 0,
            'description' => $request->description,
            'images' => array_merge([$mainImagePath], $additionalImages),
            'amenities' => $request->amenities ?? [],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'active',
        ]);

        return redirect()->route('landlord.properties')->with('success', 'Property listing created successfully!');
    }

    /**
     * Show the verify page.
     */
    public function verify(): View
    {
        $user = Auth::user();
        $verificationDocuments = null;
        $documentType = null;

        // Debug logging
        Log::info('Verify page accessed', [
            'user_id' => $user ? $user->id : null,
            'user_authenticated' => $user ? true : false,
            'valid_id_path' => $user ? $user->valid_id_path : null,
            'valid_id_back_path' => $user ? $user->valid_id_back_path : null,
            'document_type' => $user ? $user->document_type : null,
            'verification_status' => $user ? $user->verification_status : null,
        ]);

if ($user && ($user->valid_id_path || $user->valid_id_back_path)) {
    // Build verification documents array with proper image URLs
    // Only include documents that actually exist on disk
    $verificationDocuments = [];
    if ($user->valid_id_path && Storage::disk('public')->exists($user->valid_id_path)) {
        $verificationDocuments['front'] = asset('storage/' . $user->valid_id_path);
    }
    if ($user->valid_id_back_path && Storage::disk('public')->exists($user->valid_id_back_path)) {
        $verificationDocuments['back'] = asset('storage/' . $user->valid_id_back_path);
    }
}

// Set documentType if user has a document type set (regardless of current file existence)
// This ensures document type is displayed even if files were moved or paths changed
if ($user && $user->document_type) {
    $documentType = $user->document_type;
}


        // Debug logging for what we're passing to view
        Log::info('Verify page data being passed to view', [
            'verificationDocuments' => $verificationDocuments,
            'documentType' => $documentType,
            'user_id' => $user ? $user->id : null,
            'user_document_type' => $user ? $user->document_type : null,
            'user_valid_id_path' => $user ? $user->valid_id_path : null,
            'user_valid_id_back_path' => $user ? $user->valid_id_back_path : null,
        ]);

        return view('landlord.verify', compact('verificationDocuments', 'documentType'));
    }

    /**
     * Show the properties page.
     */
    public function properties(): View
    {
        $user = Auth::user();
        $listings = Listing::where('user_id', $user->id)
            ->withCount(['rentalApplications', 'rentalApplications as pending_applications_count' => function ($query) {
                $query->where('status', 'pending');
            }, 'likedBy', 'favoritedBy'])
            ->latest()
            ->paginate(12);

        return view('landlord.properties', compact('listings'));
    }

    /**
     * Show the edit listing page.
     */
    public function editListing($id): View
    {
        $user = Auth::user();
        $listing = Listing::where('user_id', $user->id)->findOrFail($id);

        return view('landlord.edit-listing', compact('listing'));
    }

    /**
     * Update a property listing.
     */
    public function updateListing(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'room_count' => 'nullable|integer|min:0',
            'bathroom_count' => 'nullable|integer|min:0',
            'location' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images' => 'nullable|array|max:9',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();
        $listing = Listing::where('user_id', $user->id)->findOrFail($id);

        $listing->update([
            'title' => $request->title,
            'price' => $request->price,
            'room_count' => $request->room_count ?? 0,
            'bathroom_count' => $request->bathroom_count ?? 0,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        // Handle image uploads
        $currentImages = $listing->images ?? [];

        // Handle main image (featured image - should be first)
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('listings', 'public');
            // Replace the first image or add as first if no images exist
            if (!empty($currentImages)) {
                $currentImages[0] = $mainImagePath;
            } else {
                $currentImages = [$mainImagePath];
            }
        }

        // Handle additional images (append to existing images)
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                if (count($currentImages) < 10) { // Respect the 10 image limit
                    $path = $image->store('listings', 'public');
                    $currentImages[] = $path;
                }
            }
        }

        // Update images if column exists
        if (Schema::hasColumn('listings', 'images')) {
            $listing->update(['images' => array_values($currentImages)]); // Re-index array
        }

        return redirect()->route('landlord.properties')->with('success', 'Property listing updated successfully!');
    }

    /**
     * Delete a property listing.
     */
    public function deleteListing($id): RedirectResponse
    {
        Log::info('Delete listing called', ['listing_id' => $id, 'user_id' => Auth::id()]);

        $user = Auth::user();
        $listing = Listing::where('user_id', $user->id)->findOrFail($id);

        Log::info('Listing found for deletion', ['listing' => $listing->toArray()]);

        // Delete associated rental applications first to avoid foreign key constraint
        $listing->rentalApplications()->delete();
        Log::info('Deleted associated rental applications', ['listing_id' => $id]);

        // Delete associated images
        if ($listing->images && is_array($listing->images)) {
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image);
                Log::info('Deleted image', ['image' => $image]);
            }
        }

        $listing->delete();

        Log::info('Listing deleted successfully', ['listing_id' => $id]);

        return redirect()->route('landlord.properties')->with('success', 'Property listing deleted successfully!');
    }

    /**
     * Show rental applications for a specific listing.
     */
    public function showApplications($listingId): View
    {
        $user = Auth::user();
        $listing = Listing::where('user_id', $user->id)->findOrFail($listingId);

        // If the property is rented, only show the accepted application
        if ($listing->isRented()) {
            $applications = $listing->rentalApplications()
                ->where('status', 'accepted')
                ->with('tenant')
                ->latest()
                ->paginate(12);
        } else {
            // If not rented, show all applications
            $applications = $listing->rentalApplications()
                ->with('tenant')
                ->latest()
                ->paginate(12);
        }

        return view('landlord.applications', compact('listing', 'applications'));
    }

    /**
     * Accept a rental application.
     */
    public function acceptApplication(Request $request, $applicationId): RedirectResponse
    {
        $user = Auth::user();
        Log::info('Accepting application', ['application_id' => $applicationId, 'user_id' => $user->id]);

        $application = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($applicationId);

        Log::info('Application found', ['application' => $application->toArray()]);

        // Get the listing
        $listing = $application->listing;

        // Accept the selected application
        $application->update(['status' => 'accepted']);

        // Automatically reject all other pending applications for this listing
        $listing->rentalApplications()
            ->where('id', '!=', $applicationId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        Log::info('Rejected other pending applications for listing', [
            'listing_id' => $listing->id,
            'accepted_application_id' => $applicationId
        ]);

        // Update the listing to mark it as rented
        Log::info('Listing before update', ['listing' => $listing->toArray()]);

        $listing->update([
            'status' => 'rented',
            'tenant_id' => $application->tenant_id,
            'lease_start_date' => $application->planned_move_in_date,
        ]);

        Log::info('Listing after update', ['listing' => $listing->fresh()->toArray()]);

        return redirect()->back()->with('success', 'Application accepted successfully! All other pending applications have been automatically rejected. The property is now marked as rented.');
    }

    /**
     * Reject a rental application.
     */
    public function rejectApplication(Request $request, $applicationId): RedirectResponse
    {
        $user = Auth::user();
        $application = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($applicationId);

        $application->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Application rejected successfully!');
    }

    /**
     * Remove a cancelled rental application.
     */
    public function removeApplication(Request $request, $applicationId): RedirectResponse
    {
        $user = Auth::user();
        $application = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($applicationId);

        // Only allow removal of cancelled applications
        if ($application->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only cancelled applications can be removed.');
        }

        // Delete the application
        $application->delete();

        return redirect()->back()->with('success', 'Cancelled application removed successfully!');
    }

    /**
     * Show the messages page.
     */
    public function messages(): View
    {
        $user = Auth::user();

        // Get all conversations (unique sender-receiver pairs)
        $conversations = \App\Models\Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver', 'listing'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function ($message) use ($user) {
            // Group by the other user in the conversation
            return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function ($messages, $otherUserId) use ($user) {
            $latestMessage = $messages->first();
            $otherUser = $latestMessage->sender_id === $user->id ? $latestMessage->receiver : $latestMessage->sender;
            $unreadCount = $messages->where('receiver_id', $user->id)->where('is_read', false)->count();

            return [
                'other_user' => $otherUser,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'listing' => $latestMessage->listing,
            ];
        });

        return view('landlord.messages', compact('conversations'));
    }

    /**
     * Show conversation with a specific user.
     */
    public function showConversation($userId)
    {
        $user = Auth::user();
        $otherUser = \App\Models\User::findOrFail($userId);

        $listing = null;
        if (request()->filled('listing_id')) {
            $listing = \App\Models\Listing::find(request('listing_id'));
        }

        // Get all messages between these two users
        $messages = \App\Models\Message::where(function ($query) use ($user, $userId) {
            $query->where(function ($q) use ($user, $userId) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($user, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $user->id);
            });
        })
        ->with(['sender', 'receiver', 'listing'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        $messages->where('receiver_id', $user->id)->where('is_read', false)->each(function ($message) {
            $message->markAsRead();
        });

        return view('landlord.conversation', compact('messages', 'otherUser', 'listing'));
    }

    /**
     * Get conversation data via AJAX for dynamic loading.
     */
    public function getConversationAjax($userId)
    {
        $user = Auth::user();
        $otherUser = \App\Models\User::findOrFail($userId);

        $listing = null;
        if (request()->filled('listing_id')) {
            $listing = \App\Models\Listing::find(request('listing_id'));
        }

        // Get all messages between these two users
        $messages = \App\Models\Message::where(function ($query) use ($user, $userId) {
            $query->where(function ($q) use ($user, $userId) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($user, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $user->id);
            });
        })
        ->with(['sender', 'receiver', 'listing'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        $messages->where('receiver_id', $user->id)->where('is_read', false)->each(function ($message) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        });

        return response()->json([
            'success' => true,
            'otherUser' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'profile_photo_path' => $otherUser->profile_photo_path,
                'role' => $otherUser->role ?? 'tenant'
            ],
            'listing' => $listing ? [
                'id' => $listing->id,
                'title' => $listing->title,
                'price' => $listing->price
            ] : null,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'subject' => $message->subject,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('M d, Y H:i'),
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'profile_photo_path' => $message->sender->profile_photo_path
                    ],
                    'receiver' => [
                        'id' => $message->receiver->id,
                        'name' => $message->receiver->name,
                        'profile_photo_path' => $message->receiver->profile_photo_path
                    ]
                ];
            })
        ]);
    }

    /**
     * Send a message to another user.
     */
    public function sendMessage(Request $request, $receiverId)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000',
            'listing_id' => 'nullable|exists:listings,id',
        ]);

        $user = Auth::user();

        $message = \App\Models\Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'listing_id' => $request->listing_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $message->load('sender');

        // Broadcast the message to the receiver
        Log::info('Broadcasting message', ['message_id' => $message->id, 'sender_id' => $message->sender_id, 'receiver_id' => $message->receiver_id]);
        broadcast(new MessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'subject' => $message->subject,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toISOString(),
                    'sender_id' => $message->sender_id,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update the landlord profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user instanceof User) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }
            return redirect()->route('landlord.profile')->with('error', 'User not found.');
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $updateData = [];

        // Handle profile info updates
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $updateData['profile_photo_path'] = $path;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
            // Refresh the user model to update session data
            Auth::setUser($user->fresh());
        }

        if ($request->expectsJson()) {
            $response = [
                'success' => true,
                'message' => 'Profile updated successfully.',
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role
            ];

            if ($request->hasFile('profile_photo')) {
                $response['image_url'] = asset('storage/' . $updateData['profile_photo_path']);
            }

            return response()->json($response);
        }

        return redirect()->route('landlord.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the landlord password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Check if current password is correct
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->route('landlord.profile')->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return redirect()->route('landlord.profile')->with('status', 'Password updated successfully.');
    }

    /**
     * Handle the submission of valid ID verification.
     */
    public function submitVerification(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'valid_id_front' => 'required|file|mimes:jpg,jpeg,png,gif|max:10240', // max 10MB
                'valid_id_back' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240', // max 10MB
                'document_type' => 'required|string|in:philippine_id,drivers_license,sss_gsis,passport,birth_certificate,other',
                'verification_notes' => 'nullable|string|max:500',
                'terms' => 'accepted',
            ]);

            /** @var User $user */
            $user = Auth::user();

            // Check if user is already verified
            if ($user->verification_status === 'approved') {
                return redirect()->route('landlord.verify')->with('error', 'Your account is already verified.');
            }

            // Handle front side upload
            if ($request->hasFile('valid_id_front')) {
                $frontPath = $request->file('valid_id_front')->store('verification_documents', 'public');
                $user->valid_id_path = $frontPath;
            }

            // Handle back side upload (optional)
            if ($request->hasFile('valid_id_back')) {
                $backPath = $request->file('valid_id_back')->store('verification_documents', 'public');
                $user->valid_id_back_path = $backPath;
            }

            // Store document type (normalize to lowercase for consistency)
            $user->document_type = strtolower($request->document_type);

            // Store additional notes if provided
            if ($request->filled('verification_notes')) {
                $user->verification_notes = $request->verification_notes;
            }

            // Generate a unique verification ID for new landlords
            if (!$user->verification_id) {
                $user->verification_id = 'VER-' . now()->format('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            }

            // Set verification status to pending for admin review
            $previousStatus = $user->verification_status;
            $user->update(['verification_status' => 'pending']);

            // Create a verification history record for admin tracking
            \App\Models\VerificationHistory::create([
                'user_id' => $user->id,
                'action' => 'submitted',
                'previous_status' => $previousStatus,
                'new_status' => 'pending',
                'verification_id' => $user->verification_id,
                'document_type' => $user->document_type,
                'notes' => $user->verification_notes,
                'metadata' => json_encode([
                    'has_front_image' => $request->hasFile('valid_id_front'),
                    'has_back_image' => $request->hasFile('valid_id_back'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]),
            ]);

            // Refresh the authenticated user to update session data
            Auth::setUser($user->fresh());

            // Log the verification submission
            Log::info('Verification submitted successfully', [
                'user_id' => $user->id,
                'verification_id' => $user->verification_id,
                'document_type' => $request->document_type,
                'has_front_image' => $request->hasFile('valid_id_front'),
                'has_back_image' => $request->hasFile('valid_id_back'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('landlord.verify')->with('success', 'Your verification documents have been submitted successfully! Our admin team will review them within 1-2 business days.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Verification submission validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('landlord.verify')
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Verification submission failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('landlord.verify')->with('error', 'An error occurred while submitting your verification. Please try again or contact support.');
        }
    }

    /**
     * Handle the upload of verification documents from dashboard.
     */
    public function uploadVerificationDocuments(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'valid_id_front' => 'required|file|mimes:jpg,jpeg,png,gif|max:10240', // max 10MB
                'valid_id_back' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240', // max 10MB
                'document_type' => 'required|string|in:philippine_id,drivers_license,sss_gsis,passport,birth_certificate,other',
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if user is already verified
            if ($user->verification_status === 'approved') {
                return redirect()->route('landlord.add-post')->with('error', 'Your account is already verified.');
            }

            // Handle front side upload
            if ($request->hasFile('valid_id_front')) {
                $frontPath = $request->file('valid_id_front')->store('verification_documents', 'public');
                $user->valid_id_path = $frontPath;
            }

            // Handle back side upload (optional)
            if ($request->hasFile('valid_id_back')) {
                $backPath = $request->file('valid_id_back')->store('verification_documents', 'public');
                $user->valid_id_back_path = $backPath;
            }

            // Store document type (normalize to lowercase for consistency)
            $user->document_type = strtolower($request->document_type);

            // Generate a unique verification ID for new landlords
            if (!$user->verification_id) {
                $user->verification_id = 'VER-' . now()->format('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            }

            // Set verification status to pending for admin review
            $user->update(['verification_status' => 'pending']);

            // Refresh the authenticated user to update session data
            Auth::setUser($user->fresh());

            // Log the verification submission
            Log::info('Verification documents uploaded from dashboard', [
                'user_id' => $user->id,
                'verification_id' => $user->verification_id,
                'document_type' => $request->document_type,
                'has_front_image' => $request->hasFile('valid_id_front'),
                'has_back_image' => $request->hasFile('valid_id_back'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('landlord.add-post')->with('success', 'Your verification documents have been uploaded successfully! Our admin team will review them within 1-2 business days.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Verification upload validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('landlord.add-post')
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Verification upload failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('landlord.add-post')->with('error', 'An error occurred while uploading your verification documents. Please try again or contact support.');
        }
    }



    /**
     * Get landlord's own verification data for modal display.
     */
    public function getVerificationData(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Build image URLs for verification documents
            // Only include documents that actually exist on disk
            $validIdFrontUrl = null;
            $validIdBackUrl = null;

            if ($user->valid_id_path && Storage::disk('public')->exists($user->valid_id_path)) {
                $validIdFrontUrl = asset('storage/' . $user->valid_id_path);
            }

            if ($user->valid_id_back_path && Storage::disk('public')->exists($user->valid_id_back_path)) {
                $validIdBackUrl = asset('storage/' . $user->valid_id_back_path);
            }

            // Get verification history if available
            $verificationHistory = [];
            if (Schema::hasTable('verification_histories')) {
                $verificationHistory = \App\Models\VerificationHistory::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($history) {
                        return [
                            'action' => $history->action,
                            'notes' => $history->notes,
                            'created_at' => $history->created_at->format('M d, Y H:i'),
                            'admin_name' => $history->admin_name ?? 'System'
                        ];
                    });
            }

            $data = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role_id,
                'verification_id' => $user->verification_id,
                'verification_status' => $user->verification_status,
                'document_type' => $user->document_type,
                'verification_notes' => $user->verification_notes,
                'submitted_at' => $user->created_at?->format('M d, Y'),
                'verified_at' => $user->verified_at?->format('M d, Y H:i'),
                'front_image_url' => $validIdFrontUrl,
                'back_image_url' => $validIdBackUrl,
                'verification_history' => $verificationHistory
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching landlord verification data', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load verification data'
            ], 500);
        }
    }

    /**
     * Allow landlord to terminate a rental (evict tenant).
     */
    public function terminateRental(Request $request, $applicationId): RedirectResponse
    {
        $user = Auth::user();

        // Find the application and ensure it belongs to the landlord's listing
        $application = \App\Models\RentalApplication::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($applicationId);

        // Check if the application is accepted and the listing is rented
        if ($application->status !== 'accepted' || !$application->listing->isRented()) {
            return redirect()->back()->with('error', 'This rental cannot be terminated.');
        }

        // Update the listing: clear tenant_id and change status to 'active'
        $listing = $application->listing;
        $listing->update([
            'tenant_id' => null,
            'status' => 'active',
            'lease_start_date' => null, // Clear lease start date as well
        ]);

        // Update the application status to 'cancelled' to allow tenant to reapply
        $application->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Rental terminated successfully. The property is now available for new applications.');
    }

    /**
     * Show payment history for landlord's properties.
     */
    public function paymentHistory(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\Payment::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['tenant', 'listing']);

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('tenant', function ($tenantQuery) use ($searchTerm) {
                    $tenantQuery->where('name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('email', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('listing', function ($listingQuery) use ($searchTerm) {
                    $listingQuery->where('title', 'like', '%' . $searchTerm . '%')
                                ->orWhere('location', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Status filter
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        // Payment method filter
        if ($request->filled('method_filter') && $request->method_filter !== 'all') {
            $query->where('payment_method', $request->method_filter);
        }

        // Month filter
        if ($request->filled('month_filter') && $request->month_filter !== 'all') {
            $query->whereMonth('payment_date', $request->month_filter);
        }

        // Year filter
        if ($request->filled('year_filter') && $request->year_filter !== 'all') {
            $query->whereYear('payment_date', $request->year_filter);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        // Calculate summary statistics
        $landlordListings = Listing::where('user_id', $user->id)->pluck('id');

        $totalReceived = \App\Models\Payment::whereIn('listing_id', $landlordListings)
            ->where('status', 'completed')
            ->sum('amount');

        $thisMonthReceived = \App\Models\Payment::whereIn('listing_id', $landlordListings)
            ->where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $pendingAmount = \App\Models\Payment::whereIn('listing_id', $landlordListings)
            ->where('status', 'pending')
            ->sum('amount');

        $activeTenants = Listing::where('user_id', $user->id)
            ->whereNotNull('tenant_id')
            ->distinct('tenant_id')
            ->count('tenant_id');

        return view('landlord.payment-history', compact('payments', 'totalReceived', 'thisMonthReceived', 'pendingAmount', 'activeTenants'));
    }

    /**
     * Show pending payment verifications for landlord's properties.
     */
    public function pendingVerifications(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\Payment::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', 'pending')
        ->with(['tenant', 'listing']);

        // Filter for manual payment methods like GCash, Maya, Bank Transfer
        $query->whereIn('payment_method', ['gcash', 'maya', 'manual', 'bank_transfer']);

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('tenant', function ($tenantQuery) use ($searchTerm) {
                    $tenantQuery->where('name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('email', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('listing', function ($listingQuery) use ($searchTerm) {
                    $listingQuery->where('title', 'like', '%' . $searchTerm . '%')
                                ->orWhere('location', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Payment method filter (only manual methods)
        if ($request->filled('method_filter') && $request->method_filter !== 'all') {
            $query->where('payment_method', $request->method_filter);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $pendingPayments = $query->orderBy('payment_date', 'desc')->paginate(20);

        // Calculate pending summary
        $landlordListings = Listing::where('user_id', $user->id)->pluck('id');
        $totalPending = \App\Models\Payment::whereIn('listing_id', $landlordListings)
            ->where('status', 'pending')
            ->whereIn('payment_method', ['gcash', 'manual', 'bank_transfer'])
            ->sum('amount');

        return view('landlord.pending-verifications', compact('pendingPayments', 'totalPending'));
    }

    /**
     * Verify a payment as received.
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        $user = Auth::user();

        $payment = \App\Models\Payment::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($paymentId);

        // Only allow verification of pending payments
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This payment has already been processed.'
            ], 400);
        }

        // Update payment status to completed
        $payment->update([
            'status' => 'completed',
            'verified_at' => now(),
            'verified_by' => $user->id
        ]);

        // Send notification to tenant
        $payment->tenant->notify(new \App\Notifications\PaymentVerifiedNotification($payment));

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully!'
        ]);
    }

    /**
     * Reject a payment.
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        $payment = \App\Models\Payment::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($paymentId);

        // Only allow rejection of pending payments
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This payment has already been processed.'
            ], 400);
        }

        // Update payment status to rejected
        $payment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $user->id,
            'rejection_reason' => $request->reason
        ]);

        // Send notification to tenant
        $payment->tenant->notify(new \App\Notifications\PaymentRejectedNotification($payment));

        return response()->json([
            'success' => true,
            'message' => 'Payment rejected successfully!'
        ]);
    }

    /**
     * Get payment details for modal display.
     */
    public function getPaymentDetails($paymentId)
    {
        $user = Auth::user();

        $payment = \App\Models\Payment::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['tenant', 'listing', 'verifier', 'rejecter'])
        ->findOrFail($paymentId);

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'amount' => number_format($payment->amount, 2),
                'payment_method' => ucfirst(str_replace('_', ' ', $payment->payment_method)),
                'payment_date' => $payment->payment_date->format('M d, Y H:i'),
                'status' => $payment->status,
                'transaction_reference' => $payment->transaction_id,
                'receipt_url' => $payment->receipt_url ? asset('storage/' . $payment->receipt_url) : null,
                'notes' => $payment->notes,
                'verified_at' => $payment->verified_at ? $payment->verified_at->format('M d, Y H:i') : null,
                'rejected_at' => $payment->rejected_at ? $payment->rejected_at->format('M d, Y H:i') : null,
                'rejection_reason' => $payment->rejection_reason,
                'verifier' => $payment->verifier ? $payment->verifier->name : null,
                'rejecter' => $payment->rejecter ? $payment->rejecter->name : null,
                'tenant' => [
                    'name' => $payment->tenant->name,
                    'email' => $payment->tenant->email,
                    'phone' => $payment->tenant->phone,
                ],
                'listing' => [
                    'title' => $payment->listing->title,
                    'location' => $payment->listing->location,
                ]
            ]
        ]);
    }

    /**
     * Show maintenance requests page.
     */
    public function maintenanceRequests(Request $request): View
    {
        $user = Auth::user();

        $query = \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['tenant', 'listing']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $issues = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'open' => \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'open')->count(),
            'in_progress' => \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'in_progress')->count(),
            'resolved' => \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'resolved')->count(),
        ];

        return view('landlord.maintenance-requests', compact('issues', 'stats'));
    }

    /**
     * Accept an issue (change status to in_progress).
     */
    public function acceptIssue(Request $request, $issueId): RedirectResponse
    {
        $user = Auth::user();

        $issue = \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($issueId);

        if ($issue->status !== 'open') {
            $currentStatus = ucfirst(str_replace('_', ' ', $issue->status));
            return redirect()->back()->with('error', "Cannot accept this maintenance request. The issue is currently '{$currentStatus}'. Only open issues can be accepted.");
        }

        $issue->update(['status' => 'in_progress']);

        return redirect()->back()->with('success', 'Maintenance request accepted successfully! The issue has been marked as "In Progress" and the tenant will be notified.');
    }

    /**
     * Resolve an issue.
     */
    public function resolveIssue(Request $request, $issueId): RedirectResponse
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        $issue = \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($issueId);

        if ($issue->status !== 'in_progress') {
            $currentStatus = ucfirst(str_replace('_', ' ', $issue->status));
            return redirect()->back()->with('error', "Cannot resolve this maintenance request. The issue is currently '{$currentStatus}'. Only issues marked as 'In Progress' can be resolved.");
        }

        $issue->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        return redirect()->back()->with('success', 'Maintenance request resolved successfully! The tenant will be notified of the resolution.');
    }

    /**
     * Send a message to the tenant about an issue.
     */
    public function messageTenant(Request $request, $issueId): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $issue = \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['tenant', 'listing'])->findOrFail($issueId);

        // Create a message
        \App\Models\Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $issue->tenant_id,
            'listing_id' => $issue->listing_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Message sent to tenant successfully.');
    }

    /**
     * Get issue details for modal display.
     */
    public function getIssueDetails($issueId)
    {
        $user = Auth::user();

        $issue = \App\Models\Issue::whereHas('listing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['tenant', 'listing'])
        ->findOrFail($issueId);

        return response()->json([
            'success' => true,
            'issue' => [
                'id' => $issue->id,
                'title' => $issue->title,
                'description' => $issue->description,
                'location' => $issue->location,
                'category' => $issue->category,
                'priority' => $issue->priority,
                'status' => $issue->status,
                'created_at' => $issue->created_at->format('M d, Y H:i'),
                'updated_at' => $issue->updated_at->format('M d, Y H:i'),
                'resolved_at' => $issue->resolved_at ? $issue->resolved_at->format('M d, Y H:i') : null,
                'resolution_notes' => $issue->resolution_notes,
                'contact_method' => $issue->contact_method,
                'available_times' => $issue->available_times,
                'photos' => $issue->photos,
                'tenant' => $issue->tenant ? [
                    'name' => $issue->tenant->name,
                    'email' => $issue->tenant->email,
                    'phone' => $issue->tenant->phone,
                ] : null,
                'listing' => $issue->listing ? [
                    'title' => $issue->listing->title,
                    'location' => $issue->listing->location,
                ] : null
            ]
        ]);
    }
}
