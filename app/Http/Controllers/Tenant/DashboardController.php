<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing; // Assuming there is a Listing model
use App\Models\User;
use App\Models\RentalApplication;
use App\Models\Payment;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the tenant dashboard with listings and filters.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Listing::where('status', 'active');

        // Apply filters if present
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('room_count')) {
            $query->where('room_count', '>=', $request->room_count);
        }
        if ($request->filled('bathroom_count')) {
            $query->where('bathroom_count', '>=', $request->bathroom_count);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Apply sorting
        $sort = $request->get('sort', 'newest');

        // Special sorting based on price filters
        if ($request->filled('min_price') && !$request->filled('max_price')) {
            // Min price filter only: sort low to high (cheapest first)
            $query->orderBy('price', 'asc');
        } elseif ($request->filled('max_price') && !$request->filled('min_price')) {
            // Max price filter only: sort high to low (most expensive first)
            $query->orderBy('price', 'desc');
        } else {
            // Normal sorting when both filters are set, neither, or explicit sort is chosen
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->leftJoin('likes', 'listings.id', '=', 'likes.listing_id')
                          ->select('listings.*', DB::raw('COUNT(likes.id) as likes_count'))
                          ->groupBy('listings.id', 'listings.title', 'listings.description', 'listings.price', 'listings.location', 'listings.room_count', 'listings.bathroom_count', 'listings.status', 'listings.user_id', 'listings.created_at', 'listings.updated_at', 'listings.featured_image', 'listings.images', 'listings.latitude', 'listings.longitude', 'listings.tenant_id', 'listings.lease_start_date')
                          ->orderBy('likes_count', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $listings = $query->paginate(10)->withQueryString();

        // Get user's favorite listing IDs
        $favoriteListingIds = $user ? $user->favoriteListings()->pluck('listings.id')->toArray() : [];

        // Get user's liked listing IDs
        $likedListingIds = $user ? $user->likedListings()->pluck('listings.id')->toArray() : [];

        // Get like counts for all listings
        $likeCounts = [];
        foreach ($listings as $listing) {
            $likeCounts[$listing->id] = $listing->likedBy()->count();
        }

        return view('tenant.dashboard', [
            'listings' => $listings,
            'filters' => $request->only(['min_price', 'max_price', 'room_count', 'bathroom_count', 'location']),
            'favoriteListingIds' => $favoriteListingIds,
            'likedListingIds' => $likedListingIds,
            'likeCounts' => $likeCounts,
        ]);
    }

    /**
     * Show the tenant's current rental details.
     */
    public function myRental()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Find the listing rented by the tenant with status 'rented'
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->with('user') // Load landlord info
            ->first();

        if ($rentalListing) {
            // Find the accepted rental application to get the lease details
            $acceptedApplication = RentalApplication::where('tenant_id', $user->id)
                ->where('listing_id', $rentalListing->id)
                ->where('status', 'accepted')
                ->first();

            if ($acceptedApplication) {
                $leaseStart = $acceptedApplication->planned_move_in_date;
                $leaseEnd = $acceptedApplication->planned_end_date; // Use the stored planned end date from the application
            } else {
                $leaseStart = $rentalListing->lease_start_date ?: now()->subMonths(6); // Default to 6 months ago for demo
                $leaseEnd = $leaseStart->copy()->addMonths(12)->subDay(); // Default to 12-month lease, last day
            }

            $rental = [
                'id' => $rentalListing->id,
                'property_name' => $rentalListing->title,
                'address' => $rentalListing->location,
                'rent_amount' => $rentalListing->price,
                'lease_start' => $leaseStart ? $leaseStart->format('Y-m-d') : null,
                'lease_end_date' => $leaseEnd ? $leaseEnd->format('Y-m-d') : null,
                'status' => ucfirst($rentalListing->status),
                'landlord' => [
                    'name' => $rentalListing->user ? $rentalListing->user->name : 'N/A',
                    'email' => $rentalListing->user ? $rentalListing->user->email : 'N/A',
                    'phone' => $rentalListing->user ? $rentalListing->user->phone : 'N/A',
                ],
            ];
        } else {
            $rental = null;
        }

        return view('tenant.my-rental', compact('rental', 'rentalListing'));
    }

    /**
     * Show property search results with filters.
     */
    public function propertySearch(Request $request)
    {
        $query = Listing::where('status', 'active');

        // Apply filters if present
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('room_count')) {
            $query->where('room_count', '>=', $request->room_count);
        }
        if ($request->filled('bathroom_count')) {
            $query->where('bathroom_count', '>=', $request->bathroom_count);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $listings = $query->paginate(12)->withQueryString();

        return view('tenant.property-search', [
            'listings' => $listings,
            'filters' => $request->only(['min_price', 'max_price', 'room_count', 'bathroom_count', 'location']),
        ]);
    }

    /**
     * Show tenant's favorite properties.
     */
    public function favorites()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get user's favorite listings with related data, only active and not banned
        $favorites = $user->favoriteListings()
            ->where('status', 'active')
            ->with(['user:id,name,profile_photo_path'])
            ->paginate(12);

        // Get user's favorite listing IDs for Alpine.js, only active and not banned
        $favoriteListingIds = $user->favoriteListings()
            ->where('status', 'active')
            ->pluck('listings.id')
            ->toArray();

        // Get user's liked listing IDs for Alpine.js, only active and not banned
        $likedListingIds = $user->likedListings()
            ->where('status', 'active')
            ->pluck('listings.id')
            ->toArray();

        // Get like counts for favorite listings
        $likeCounts = [];
        foreach ($favorites as $listing) {
            $likeCounts[$listing->id] = $listing->likedBy()->count();
        }

        return view('tenant.favorites', compact('favorites', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
    }

    /**
     * Show properties on a map view.
     */
    public function propertyMap()
    {
        // Fetch real listings from database that have latitude and longitude
        $listings = Listing::whereNotNull('latitude')
                          ->whereNotNull('longitude')
                          ->where('status', 'available')
                          ->get();

        // Format properties for the map
        $properties = $listings->map(function($listing) {
            return [
                'id' => $listing->id,
                'title' => $listing->title,
                'lat' => (float) $listing->latitude,
                'lng' => (float) $listing->longitude,
                'price' => (float) $listing->price,
                'address' => $listing->location ?? 'Address not specified'
            ];
        });

        // If no real listings with coordinates, use sample data for Gonzaga, Cagayan
        if ($properties->isEmpty()) {
            $properties = collect([
                [
                    'id' => 1,
                    'title' => 'Modern Apartment in Progressive',
                    'lat' => 18.264505,
                    'lng' => 121.991013,
                    'price' => 8500,
                    'address' => 'Progressive Village, Gonzaga'
                ],
                [
                    'id' => 2,
                    'title' => 'Cozy House in Paradise',
                    'lat' => 18.260825,
                    'lng' => 121.991313,
                    'price' => 12000,
                    'address' => 'Paradise Subdivision, Gonzaga'
                ],
                [
                    'id' => 3,
                    'title' => 'Spacious Townhouse in Smart',
                    'lat' => 18.260152,
                    'lng' => 121.996589,
                    'price' => 15000,
                    'address' => 'Smart Village, Gonzaga'
                ]
            ]);
        }

        return view('tenant.property-map', compact('properties'));
    }

    /**
     * Show advanced search with more filters.
     */
    public function advancedSearch(Request $request)
    {
        $query = Listing::where('status', 'active');

        // Advanced filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('min_rooms')) {
            $query->where('room_count', '>=', $request->min_rooms);
        }
        if ($request->filled('max_rooms')) {
            $query->where('room_count', '<=', $request->max_rooms);
        }
        if ($request->filled('min_bathrooms')) {
            $query->where('bathroom_count', '>=', $request->min_bathrooms);
        }
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }
        if ($request->filled('amenities')) {
            $amenities = explode(',', $request->amenities);
            foreach ($amenities as $amenity) {
                $query->where('amenities', 'like', '%' . trim($amenity) . '%');
            }
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $listings = $query->paginate(12)->withQueryString();

        return view('tenant.advanced-search', [
            'listings' => $listings,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show property details page.
     */
    public function propertyDetails($id)
    {
        // Find the listing by ID
        /** @var \App\Models\Listing $listing */
        $listing = Listing::findOrFail($id);

        // Get current user's favorite listing IDs
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $favoriteListingIds = $user ? $user->favoriteListings()->pluck('listings.id')->toArray() : [];

        // Get current user's liked listing IDs
        $likedListingIds = $user ? $user->likedListings()->pluck('listings.id')->toArray() : [];

        // Get like count for this listing
        $likeCounts = [$listing->id => $listing->likedBy()->count()];

        // Check if tenant can apply (no active rental and no pending applications, or pending application for this property)
        $canApply = true;
        if ($user) {
            $activeRental = Listing::where('tenant_id', $user->id)->where('status', 'rented')->first();
            $pendingApplicationForCurrentProperty = RentalApplication::where('tenant_id', $user->id)
                ->where('listing_id', $listing->id)
                ->where('status', 'pending')
                ->first();
            $anyPendingApplication = RentalApplication::where('tenant_id', $user->id)
                ->where('status', 'pending')
                ->first();
            $canApply = !$activeRental && (!$anyPendingApplication || $pendingApplicationForCurrentProperty);
        }

        // You might want to add additional logic here, such as:
        // - Check if the user has permission to view this listing
        // - Load related data like landlord info, images, etc.
        // - Track views/analytics

        return view('tenant.property-details', compact('listing', 'favoriteListingIds', 'likedListingIds', 'likeCounts', 'canApply'));
    }

    /**
     * Show user profile and their listings.
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);

        // Get user's active listings only
        $listings = $user->listings()->where('status', 'active')->paginate(12);

        // Get current user's favorite listing IDs
        $currentUser = auth()->user();
        $favoriteListingIds = $currentUser ? $currentUser->favoriteListings()->pluck('listings.id')->toArray() : [];

        // Get current user's liked listing IDs
        $likedListingIds = $currentUser ? $currentUser->likedListings()->pluck('listings.id')->toArray() : [];

        // Get like counts for all listings
        $likeCounts = [];
        foreach ($listings as $listing) {
            $likeCounts[$listing->id] = $listing->likedBy()->count();
        }

        return view('tenant.user-profile', compact('user', 'listings', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
    }

    /**
     * Toggle favorite status for a listing.
     */
    public function toggleFavorite(Listing $listing)
    {
        $user = Auth::user();

        if ($user->favoriteListings()->where('listing_id', $listing->id)->exists()) {
            // Remove from favorites
            $user->favoriteListings()->detach($listing->id);
            $message = 'Property removed from favorites';
        } else {
            // Add to favorites
            $user->favoriteListings()->attach($listing->id);
            $message = 'Property added to favorites';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $user->favoriteListings()->where('listing_id', $listing->id)->exists()
        ]);
    }

    /**
     * Toggle like status for a listing.
     */
    public function toggleLike(Listing $listing)
    {
        $user = Auth::user();
        $listingId = $listing->id;

        if ($user) {
            // Authenticated user - use database
            if ($user->likedListings()->where('listing_id', $listingId)->exists()) {
                // Remove from likes
                $user->likedListings()->detach($listingId);
                $message = 'Property unliked';
            } else {
                // Add to likes
                $user->likedListings()->attach($listingId);
                $message = 'Property liked';
            }

            // Get updated like count
            $likeCount = $listing->likedBy()->count();
            $isLiked = $user->likedListings()->where('listing_id', $listingId)->exists();
        } else {
            // Unauthenticated user - use session storage
            $guestLikes = session('guest_likes', []);

            if (in_array($listingId, $guestLikes)) {
                // Remove from likes
                $guestLikes = array_diff($guestLikes, [$listingId]);
                $message = 'Property unliked';
            } else {
                // Add to likes
                $guestLikes[] = $listingId;
                $message = 'Property liked';
            }

            // Store updated likes in session
            session(['guest_likes' => array_values($guestLikes)]);

            // Get updated like count (database + session)
            $dbLikeCount = $listing->likedBy()->count();
            $sessionLikeCount = in_array($listingId, $guestLikes) ? 1 : 0;
            $likeCount = $dbLikeCount + $sessionLikeCount;
            $isLiked = in_array($listingId, $guestLikes);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_liked' => $isLiked,
            'like_count' => $likeCount
        ]);
    }

    /**
     * Show tenant's rental applications.
     */
    public function myApplications()
    {
        $user = Auth::user();

        // Get all applications for the current tenant
        $applications = $user->rentalApplications()
            ->with(['listing.user:id,name,profile_photo_path'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('tenant.applications', compact('applications'));
    }

    /**
     * Show tenant messages.
     */
    public function messages()
    {
        $user = Auth::user();

        // Get all existing conversations (unique sender-receiver pairs)
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
                'has_messages' => true,
            ];
        });

        return view('tenant.messages', compact('conversations'));
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

        return view('tenant.conversation', compact('messages', 'otherUser', 'listing'));
    }

    /**
     * Get conversation data via AJAX.
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
            $message->markAsRead();
        });

        return response()->json([
            'success' => true,
            'otherUser' => $otherUser,
            'listing' => $listing,
            'messages' => $messages->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('M d, Y H:i'),
                    'is_sender' => $message->sender_id === $user->id,
                    'sender_name' => $message->sender->name,
                    'sender_avatar' => $message->sender->profile_photo_path ? asset('storage/' . $message->sender->profile_photo_path) : asset('images/default-avatar.png'),
                ];
            }),
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
     * Show rental application form for a listing.
     */
    public function showRentalApplication(Listing $listing)
    {
        $user = Auth::user();

        // Check if tenant has an active rental
        $activeRental = Listing::where('tenant_id', $user->id)->where('status', 'rented')->first();
        if ($activeRental) {
            return redirect()->route('tenant.property-details', $listing->id)
                ->with('error', 'You need to leave your current property before applying for a new one.');
        }

        // Check if reapply query parameter is present
        if (request()->query('reapply') == 1) {
            // Find and cancel the existing rejected application for this listing
            $existingRejectedApplication = $listing->rentalApplications()
                ->where('tenant_id', $user->id)
                ->where('status', 'rejected')
                ->first();

            if ($existingRejectedApplication) {
                // Cancel the rejected application
                $existingRejectedApplication->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
            }

            // Show rental application form to allow reapplying
            return view('tenant.rental-application', compact('listing'));
        }

        // Check if user has an active (non-cancelled) application for this listing
        $existingApplication = $listing->rentalApplications()
            ->where('tenant_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingApplication) {
            // Load favorites and likes data like in propertyDetails
            $favoriteListingIds = $user ? $user->favoriteListings()->pluck('listings.id')->toArray() : [];
            $likedListingIds = $user ? $user->likedListings()->pluck('listings.id')->toArray() : [];
            $likeCounts = [$listing->id => $listing->likedBy()->count()];

            // Show application status page with edit option
            return view('tenant.application-status', compact('listing', 'existingApplication', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
        }

        return view('tenant.rental-application', compact('listing'));
    }

    /**
     * Store rental application.
     */
    public function storeRentalApplication(Request $request, Listing $listing)
    {
        $user = Auth::user();

        // Check if tenant is already actively renting a property
        $activeRental = Listing::where('tenant_id', $user->id)->where('status', 'rented')->first();
        if ($activeRental) {
            return redirect()->route('tenant.property-details', $listing->id)
                ->with('error', 'You need to leave your current property before applying for a new one.');
        }

        // Validate the request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'planned_move_in_date' => 'required|date|after:today',
            'rental_duration' => 'required|integer|min:1|max:24',
            'planned_end_date' => 'required|date|after:planned_move_in_date',
            'employment_status' => 'required|in:employed,self-employed,student,unemployed,retired',
            'monthly_income' => 'required|numeric|min:0',
            'occupants' => 'required|integer|min:1',
            'reason_for_moving' => 'required|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Calculate and validate planned_end_date based on rental_duration
        $moveInDate = new \DateTime($validated['planned_move_in_date']);
        $calculatedEndDate = clone $moveInDate;
        $calculatedEndDate->modify('+' . $validated['rental_duration'] . ' months');
        $calculatedEndDate->modify('-1 day'); // End date is the last day of the rental period

        $providedEndDate = new \DateTime($validated['planned_end_date']);

        // Check if the provided end date matches the calculated one (allowing for small differences)
        $dateDiff = $calculatedEndDate->diff($providedEndDate)->days;
        if ($dateDiff > 1) { // Allow 1 day tolerance for potential timezone or calculation differences
            return back()->withErrors(['planned_end_date' => 'The calculated end date does not match the selected rental duration.'])->withInput();
        }

        // Check if user has an active (non-cancelled, non-rejected) application
        $existingApplication = $listing->rentalApplications()
            ->where('tenant_id', $user->id)
            ->where('status', 'not in', ['cancelled', 'rejected'])
            ->first();
        if ($existingApplication) {
            return redirect()->route('tenant.property-details', $listing->id)
                ->with('error', 'You have already applied for this property.');
        }

        // Handle file upload if provided
        $documentUrl = null;
        if ($request->hasFile('document')) {
            $documentUrl = $request->file('document')->store('rental-applications', 'public');
        }

        // Create the application
        $application = RentalApplication::create([
            'tenant_id' => $user->id,
            'listing_id' => $listing->id,
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'planned_move_in_date' => $validated['planned_move_in_date'],
            'planned_end_date' => $validated['planned_end_date'],
            'employment_status' => $validated['employment_status'],
            'monthly_income' => $validated['monthly_income'],
            'occupants' => $validated['occupants'],
            'reason_for_moving' => $validated['reason_for_moving'],
            'additional_notes' => $validated['additional_notes'],
            'document_url' => $documentUrl,
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.property-details', $listing->id)
            ->with('success', 'Your rental application has been submitted successfully!');
    }

    /**
     * Show edit rental application form.
     */
    public function editRentalApplication(RentalApplication $application)
    {
        $user = Auth::user();

        // Check if the application belongs to the current user
        if ($application->tenant_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $listing = $application->listing;

        // Load favorites and likes data
        $favoriteListingIds = $user->favoriteListings()->pluck('listings.id')->toArray();
        $likedListingIds = $user->likedListings()->pluck('listings.id')->toArray();
        $likeCounts = [$listing->id => $listing->likedBy()->count()];

        return view('tenant.rental-application', compact('listing', 'application', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
    }

    /**
     * Update rental application.
     */
    public function updateRentalApplication(Request $request, RentalApplication $application)
    {
        $user = Auth::user();

        // Check if the application belongs to the current user
        if ($application->tenant_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate the request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'planned_move_in_date' => 'required|date|after:today',
            'rental_duration' => 'required|integer|min:1|max:24',
            'planned_end_date' => 'required|date|after:planned_move_in_date',
            'employment_status' => 'required|in:employed,self-employed,student,unemployed,retired',
            'monthly_income' => 'required|numeric|min:0',
            'occupants' => 'required|integer|min:1',
            'reason_for_moving' => 'required|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Calculate and validate planned_end_date based on rental_duration
        $moveInDate = new \DateTime($validated['planned_move_in_date']);
        $calculatedEndDate = clone $moveInDate;
        $calculatedEndDate->modify('+' . $validated['rental_duration'] . ' months');
        $calculatedEndDate->modify('-1 day'); // End date is the last day of the rental period

        $providedEndDate = new \DateTime($validated['planned_end_date']);

        // Check if the provided end date matches the calculated one (allowing for small differences)
        $dateDiff = $calculatedEndDate->diff($providedEndDate)->days;
        if ($dateDiff > 1) { // Allow 1 day tolerance for potential timezone or calculation differences
            return back()->withErrors(['planned_end_date' => 'The calculated end date does not match the selected rental duration.'])->withInput();
        }

        // Handle file upload if provided
        if ($request->hasFile('document')) {
            // Delete old file if exists
            if ($application->document_url) {
                Storage::disk('public')->delete($application->document_url);
            }
            $documentUrl = $request->file('document')->store('rental-applications', 'public');
            $validated['document_url'] = $documentUrl;
        }

        // Update the application
        $application->update($validated);

        return redirect()->route('tenant.rental-application.show', $application->listing_id)
            ->with('success', 'Your rental application has been updated successfully!');
    }

    /**
     * Cancel rental application with 1-hour undo window.
     */
    public function cancelRentalApplication(RentalApplication $application)
    {
        $user = Auth::user();

        // Check if the application belongs to the current user
        if ($application->tenant_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Check if application is already cancelled
        if ($application->cancelled_at) {
            return redirect()->back()->with('error', 'Application is already cancelled.');
        }

        // Check if application is already accepted or rejected
        if (in_array($application->status, ['accepted', 'rejected'])) {
            return redirect()->back()->with('error', 'Cannot cancel an application that has been reviewed.');
        }

        // Set cancelled_at timestamp
        $application->update([
            'cancelled_at' => now(),
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Application cancelled. You have 1 hour to undo this action.');
    }

    /**
     * Undo cancel rental application within 1 hour.
     */
    public function undoCancelRentalApplication(RentalApplication $application)
    {
        $user = Auth::user();

        // Check if the application belongs to the current user
        if ($application->tenant_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Check if application is cancelled
        if (!$application->cancelled_at) {
            return redirect()->back()->with('error', 'Application is not cancelled.');
        }

        // Check if within 1 hour window
        if ($application->cancelled_at->addHour()->isPast()) {
            return redirect()->back()->with('error', 'Undo window has expired. Application cannot be restored.');
        }

        // Restore application
        $application->update([
            'cancelled_at' => null,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Application restored successfully.');
    }

    /**
     * Allow tenant to leave their rental property.
     */
    public function leaveRental()
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        // Update the listing: clear tenant_id and change status to 'active'
        $rentalListing->update([
            'tenant_id' => null,
            'status' => 'active',
            'lease_start_date' => null, // Clear lease start date as well
        ]);

        // Find and update any active application (accepted, pending, etc.) status to 'cancelled'
        $activeApplication = RentalApplication::where('tenant_id', $user->id)
            ->where('listing_id', $rentalListing->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        Log::info('Leave rental debug', [
            'user_id' => $user->id,
            'listing_id' => $rentalListing->id,
            'application_found' => $activeApplication ? true : false,
            'application_status' => $activeApplication ? $activeApplication->status : null,
            'application_id' => $activeApplication ? $activeApplication->id : null
        ]);

        if ($activeApplication) {
            $activeApplication->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);
        }

        return redirect()->route('tenant.rental')->with('success', 'You have successfully left the rental property.');
    }

    /**
     * Show tenant profile page.
     */
    public function profile()
    {
        return view('tenant.profile');
    }

    /**
     * Update tenant profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Check if this is an AJAX request for profile photo upload
        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            try {
                // Validate only profile photo for AJAX requests
                $validated = $request->validate([
                    'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
                ]);

                // Handle profile photo upload
                if ($request->hasFile('profile_photo')) {
                    // Delete old profile photo if exists
                    if ($user->profile_photo_path) {
                        Storage::disk('public')->delete($user->profile_photo_path);
                    }

                    // Store new profile photo
                    $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                    $user->update(['profile_photo_path' => $profilePhotoPath]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully!',
                    'photo_url' => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                \Log::error('Profile photo upload error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload profile photo. Please try again.'
                ], 500);
            }
        }

        // Handle full profile update for regular form submission
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new profile photo
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $profilePhotoPath;
        }

        // Update user
        $user->update($validated);

        return redirect()->route('tenant.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update tenant password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('tenant.profile')->with('password_success', 'Password changed successfully!');
    }

    /**
     * Show the public listings page with listings and filters (no authentication required).
     */
    public function publicListings(Request $request)
    {
        $query = Listing::where('status', 'active');

        // Apply filters if present
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('room_count')) {
            $query->where('room_count', '>=', $request->room_count);
        }
        if ($request->filled('bathroom_count')) {
            $query->where('bathroom_count', '>=', $request->bathroom_count);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Apply sorting
        $sort = $request->get('sort', 'newest');

        // Special sorting based on price filters
        if ($request->filled('min_price') && !$request->filled('max_price')) {
            // Min price filter only: sort low to high (cheapest first)
            $query->orderBy('price', 'asc');
        } elseif ($request->filled('max_price') && !$request->filled('min_price')) {
            // Max price filter only: sort high to low (most expensive first)
            $query->orderBy('price', 'desc');
        } else {
            // Normal sorting when both filters are set, neither, or explicit sort is chosen
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->leftJoin('likes', 'listings.id', '=', 'likes.listing_id')
                          ->select('listings.*', DB::raw('COUNT(likes.id) as likes_count'))
                          ->groupBy('listings.id', 'listings.title', 'listings.description', 'listings.price', 'listings.location', 'listings.room_count', 'listings.bathroom_count', 'listings.status', 'listings.user_id', 'listings.created_at', 'listings.updated_at', 'listings.featured_image', 'listings.images', 'listings.latitude', 'listings.longitude', 'listings.tenant_id', 'listings.lease_start_date')
                          ->orderBy('likes_count', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $listings = $query->paginate(10)->withQueryString();

        // For public access, set empty arrays for favorites and get session likes
        $favoriteListingIds = [];
        $likedListingIds = session('guest_likes', []);

        // Get like counts for all listings (including session likes)
        $likeCounts = [];
        foreach ($listings as $listing) {
            $dbLikeCount = $listing->likedBy()->count();
            $sessionLikeCount = in_array($listing->id, $likedListingIds) ? 1 : 0;
            $likeCounts[$listing->id] = $dbLikeCount + $sessionLikeCount;
        }

        return view('listings', [
            'listings' => $listings,
            'filters' => $request->only(['min_price', 'max_price', 'room_count', 'bathroom_count', 'location']),
            'favoriteListingIds' => $favoriteListingIds,
            'likedListingIds' => $likedListingIds,
            'likeCounts' => $likeCounts,
        ]);
    }

    /**
     * Show public property details page (no authentication required).
     */
    public function publicPropertyDetails($id)
    {
        // Find the listing by ID
        /** @var \App\Models\Listing $listing */
        $listing = Listing::findOrFail($id);

        // For public access, set empty arrays for favorites and get session likes
        $favoriteListingIds = [];
        $likedListingIds = session('guest_likes', []);

        // Get like count for this listing (including session likes)
        $dbLikeCount = $listing->likedBy()->count();
        $sessionLikeCount = in_array($listing->id, $likedListingIds) ? 1 : 0;
        $likeCounts = [$listing->id => $dbLikeCount + $sessionLikeCount];

        // For public users, they cannot apply - set canApply to false
        $canApply = false;

        return view('property-details', compact('listing', 'favoriteListingIds', 'likedListingIds', 'likeCounts', 'canApply'));
    }

    /**
     * Show lease details for the tenant's current rental.
     */
    public function viewLease()
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->with('user')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        return view('tenant.lease', compact('rentalListing'));
    }

    /**
     * Show rent payment form.
     */
    public function showPayRent()
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        // Fetch recent payments for this tenant and listing
        $recentPayments = Payment::where('tenant_id', $user->id)
            ->where('listing_id', $rentalListing->id)
            ->orderBy('payment_date', 'desc')
            ->take(5)
            ->get();

        // No fixed due date - tenants can pay any day
        $dueDate = 'Any day of the month';

        return view('tenant.pay-rent', compact('rentalListing', 'recentPayments', 'dueDate'));
    }

    /**
     * Process rent payment.
     */
    public function payRent(Request $request)
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        // Validate amount is at least ₱1.00 (no upper limit since tenants can pay any amount any day)
        if ($request->amount < 1) {
            return back()->withErrors(['amount' => 'Payment amount must be at least ₱1.00'])->withInput();
        }

        // Validate the request based on payment method
        $rules = [
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:gcash,maya,bank_transfer',
            'notes' => 'nullable|string|max:500',
            'receipt' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ];

        if ($request->payment_method === 'gcash') {
            $rules = array_merge($rules, [
                'gcash_number' => ['required', 'string', function ($attribute, $value, $fail) {
                    if (!preg_match('/^09\d{9}$/', $value) && !preg_match('/^\+63\d{10}$/', $value)) {
                        $fail('The ' . $attribute . ' must be a valid Philippine mobile number.');
                    }
                }],
                'gcash_reference_number' => 'nullable|string|max:255',
            ]);
        } elseif ($request->payment_method === 'maya') {
            $rules = array_merge($rules, [
                'maya_number' => ['required', 'string', function ($attribute, $value, $fail) {
                    if (!preg_match('/^09\d{9}$/', $value) && !preg_match('/^\+63\d{10}$/', $value)) {
                        $fail('The ' . $attribute . ' must be a valid Philippine mobile number.');
                    }
                }],
                'maya_reference_number' => 'nullable|string|max:255',
            ]);
        } elseif ($request->payment_method === 'bank_transfer') {
            $rules = array_merge($rules, [
                'bank_reference_number' => 'nullable|string|max:255',
            ]);
        }

        $validated = $request->validate($rules);

        // Handle receipt upload if provided
        $receiptUrl = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $receiptUrl = $file->storeAs('payment-receipts', $filename, 'public');
        }

        // No fixed due date - payments are always considered on time
        $isOnTime = true;

        // Determine payment status based on method
        // Manual payment methods require verification by landlord
        $manualPaymentMethods = ['gcash', 'bank_transfer', 'maya', 'qr_code'];
        $paymentStatus = in_array($validated['payment_method'], $manualPaymentMethods) ? 'pending' : 'completed';

        // Log the payment attempt
        Log::info('Rent payment initiated', [
            'tenant_id' => $user->id,
            'listing_id' => $rentalListing->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'is_on_time' => $isOnTime,
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ]);

        // Create payment record
        $payment = \App\Models\Payment::create([
            'tenant_id' => $user->id,
            'listing_id' => $rentalListing->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => $paymentStatus,
            'payment_date' => now(),
            'notes' => $validated['notes'] ?? null,
            'receipt_url' => $receiptUrl,
            'is_on_time' => $isOnTime,
            'transaction_details' => json_encode(array_diff_key($validated, array_flip(['amount', 'payment_method', 'notes']))),
        ]);

        Log::info('Rent payment created successfully', [
            'payment_id' => $payment->id,
            'status' => $paymentStatus,
        ]);

        // Send notifications
        try {
            $landlord = $rentalListing->user;
            if ($landlord) {
                $landlord->notify(new \App\Notifications\PaymentReceivedNotification($payment));
            }

            // Send receipt to tenant
            $user->notify(new \App\Notifications\PaymentReceiptNotification($payment));

            // Notify admins (optional, for monitoring)
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\PaymentReceivedNotification($payment));
            }
        } catch (\Exception $e) {
            // Log notification failure but don't fail the payment
            Log::error('Failed to send payment notifications: ' . $e->getMessage());
        }

        return redirect()->route('tenant.rental')->with('success', 'Rent payment submitted successfully! A receipt has been sent to your email.');
    }

    /**
     * Show maintenance issue report form.
     */
    public function showReportIssue()
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        return view('tenant.report-issue', compact('rentalListing'));
    }

    /**
     * Submit maintenance issue report.
     */
    public function reportIssue(Request $request)
    {
        $user = Auth::user();

        // Find the listing rented by the tenant
        $rentalListing = Listing::where('tenant_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rentalListing) {
            return redirect()->route('tenant.rental')->with('error', 'No active rental found.');
        }

        // Validate the request
        $validated = $request->validate([
            'category' => 'required|in:plumbing,electrical,structural,appliance,pest_control,cleaning,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'urgency' => 'required|in:low,medium,high,emergency',
            'location' => 'nullable|string|max:255',
            'contact_method' => 'required|in:phone,email,text',
            'available_times' => 'nullable|string|max:500',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per photo
            'terms' => 'required|accepted',
        ]);

        // Handle photo uploads
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoUrls[] = $photo->store('issue-photos', 'public');
            }
        }

        // Create issue record
        \App\Models\Issue::create([
            'tenant_id' => $user->id,
            'listing_id' => $rentalListing->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['urgency'] == 'emergency' ? 'urgent' : $validated['urgency'], // Map urgency to priority
            'category' => $validated['category'],
            'location' => $validated['location'],
            'contact_method' => $validated['contact_method'],
            'available_times' => $validated['available_times'],
            'photos' => $photoUrls,
            'status' => 'open',
        ]);

        return redirect()->route('tenant.rental')->with('success', 'Maintenance issue reported successfully!');
    }


}
