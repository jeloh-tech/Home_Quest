<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Services\VerificationService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        // Get total counts
        $totalUsers = User::count();
        $totalTenants = User::where('role', 'tenant')->count();
        $totalLandlords = User::where('role', 'landlord')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalPosts = Listing::count();

        // Get actual counts based on status
        $activePosts = Listing::where('status', 'active')->count();
        $rentedProperties = Listing::where('status', 'rented')->count();

        // Calculate percentages
        $activePostsPercentage = $totalPosts > 0 ? round(($activePosts / $totalPosts) * 100, 1) : 0;
        $rentedPropertiesPercentage = $totalPosts > 0 ? round(($rentedProperties / $totalPosts) * 100, 1) : 0;

        // Get monthly user registration data for the last 12 months
        $monthlyUsers = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyUsers[] = [
                'month' => $date->format('M Y'),
                'count' => User::whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->count()
            ];
        }

        // Get user role distribution
        $userRoleData = [
            'tenants' => $totalTenants,
            'landlords' => $totalLandlords,
            'admins' => $totalAdmins
        ];

        // Get listing status distribution
        $listingStatusData = [
            'active' => $activePosts,
            'rented' => $rentedProperties,
            'inactive' => Listing::where('status', 'inactive')->count(),
            'banned' => Listing::where('status', 'banned')->count()
        ];

        // Get recent payments data for the last 6 months
        $monthlyPayments = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyPayments[] = [
                'month' => $date->format('M Y'),
                'amount' => \App\Models\Payment::where('status', 'completed')
                                              ->whereYear('payment_date', $date->year)
                                              ->whereMonth('payment_date', $date->month)
                                              ->sum('amount')
            ];
        }

        // Get top performing landlords (by number of properties)
        $topLandlords = User::where('role', 'landlord')
                           ->withCount('listings')
                           ->having('listings_count', '>', 0)
                           ->orderBy('listings_count', 'desc')
                           ->paginate(3);

        // Get pending verifications count (only landlords)
        $pendingVerifications = User::where('role', 'landlord')->whereIn('verification_status', ['pending', null])->count();

        // Get recent payments (last 5)
        $recentPayments = \App\Models\Payment::with(['tenant', 'listing.user'])
                                           ->orderBy('created_at', 'desc')
                                           ->take(5)
                                           ->get();

        // Get maintenance requests count
        $maintenanceRequests = \App\Models\Issue::where('status', 'pending')->count();

        // Get system alerts (banned users, disputed payments, etc.)
        $bannedUsers = User::where('verification_status', 'banned')->count();
        $disputedPayments = \App\Models\Payment::where('status', 'disputed')->count();

        // Get user verification status distribution
        $verificationStatusData = [
            'approved' => User::where('verification_status', 'approved')->count(),
            'pending' => User::whereIn('verification_status', ['pending', null])->count(),
            'banned' => $bannedUsers,
            'rejected' => User::where('verification_status', 'rejected')->count()
        ];

        // Get payment status distribution
        $paymentStatusData = [
            'completed' => \App\Models\Payment::where('status', 'completed')->count(),
            'pending' => \App\Models\Payment::where('status', 'pending')->count(),
            'failed' => \App\Models\Payment::where('status', 'failed')->count(),
            'refunded' => \App\Models\Payment::where('status', 'refunded')->count(),
            'disputed' => $disputedPayments
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTenants',
            'totalLandlords',
            'totalAdmins',
            'totalPosts',
            'activePosts',
            'activePostsPercentage',
            'rentedProperties',
            'rentedPropertiesPercentage',
            'monthlyUsers',
            'userRoleData',
            'listingStatusData',
            'monthlyPayments',
            'topLandlords',
            'pendingVerifications',
            'recentPayments',
            'maintenanceRequests',
            'bannedUsers',
            'disputedPayments',
            'verificationStatusData',
            'paymentStatusData'
        ));
    }

    /**
     * Show all users.
     */
    public function users(Request $request): View
    {
        $landlordQuery = User::where('role', 'landlord')->withCount('listings');
        $tenantQuery = User::where('role', 'tenant');
        $adminQuery = User::where('role', 'admin');

        // Unified search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $landlordQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
            $tenantQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
            $adminQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Individual search filters (for backward compatibility)
        if ($request->filled('landlord_search')) {
            $searchTerm = $request->landlord_search;
            $landlordQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // New landlord table search functionality
        if ($request->filled('landlord_table_search')) {
            $searchTerm = $request->landlord_table_search;
            $landlordQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('tenant_search')) {
            $searchTerm = $request->tenant_search;
            $tenantQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('admin_search')) {
            $searchTerm = $request->admin_search;
            $adminQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $status = $request->status_filter;
            $landlordQuery->where('verification_status', $status);
            $tenantQuery->where('verification_status', $status);
            $adminQuery->where('verification_status', $status);
        }

        // Role filter
        if ($request->filled('role_filter') && $request->role_filter !== 'all') {
            $role = $request->role_filter;
            if ($role === 'landlord') {
                $tenantQuery->whereRaw('1=0'); // No results for tenants
                $adminQuery->whereRaw('1=0'); // No results for admins
            } elseif ($role === 'tenant') {
                $landlordQuery->whereRaw('1=0'); // No results for landlords
                $adminQuery->whereRaw('1=0'); // No results for admins
            } elseif ($role === 'admin') {
                $landlordQuery->whereRaw('1=0'); // No results for landlords
                $tenantQuery->whereRaw('1=0'); // No results for tenants
            }
        }

        // Sorting
        $allowedSorts = ['name', 'email', 'created_at', 'verification_status'];
        $landlordSort = $request->input('landlord_sort', 'name');
        $tenantSort = $request->input('tenant_sort', 'created_at');
        $adminSort = $request->input('admin_sort', 'created_at');
        $landlordDirection = $request->input('landlord_direction', 'asc');
        $tenantDirection = $request->input('tenant_direction', 'desc');
        $adminDirection = $request->input('admin_direction', 'desc');

        if (!in_array($landlordSort, $allowedSorts)) {
            $landlordSort = 'created_at';
        }
        if (!in_array($tenantSort, $allowedSorts)) {
            $tenantSort = 'created_at';
        }
        if (!in_array($adminSort, $allowedSorts)) {
            $adminSort = 'created_at';
        }
        if (!in_array($landlordDirection, ['asc', 'desc'])) {
            $landlordDirection = 'desc';
        }
        if (!in_array($tenantDirection, ['asc', 'desc'])) {
            $tenantDirection = 'desc';
        }
        if (!in_array($adminDirection, ['asc', 'desc'])) {
            $adminDirection = 'desc';
        }

        $landlords = $landlordQuery->orderBy($landlordSort, $landlordDirection)->paginate(7, ['*'], 'landlords_page')->appends($request->except('tenants_page', 'admins_page'));

        // For tenant search, limit to 2 results if searching
        if ($request->filled('tenant_search')) {
            $tenants = $tenantQuery->orderBy($tenantSort, $tenantDirection)->take(2)->get();
            // Convert to paginator for consistency with view
            $tenants = new \Illuminate\Pagination\LengthAwarePaginator(
                $tenants,
                $tenants->count(),
                2,
                1,
                ['path' => request()->url(), 'pageName' => 'tenants_page']
            );
        } else {
            $tenants = $tenantQuery->orderBy($tenantSort, $tenantDirection)->paginate(7, ['*'], 'tenants_page')->appends($request->except('landlords_page', 'admins_page'));
        }

        $admins = $adminQuery->orderBy($adminSort, $adminDirection)->paginate(7, ['*'], 'admins_page')->appends($request->except('landlords_page', 'tenants_page'));

        // Get statistics for the overview cards
        $stats = [
            'total_users' => User::count(),
            'total_landlords' => User::where('role', 'landlord')->count(),
            'total_tenants' => User::where('role', 'tenant')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'active_users' => User::where('verification_status', 'approved')->count(),
            'pending_users' => User::where('verification_status', 'pending')->orWhereNull('verification_status')->count(),
            'banned_users' => User::where('verification_status', 'banned')->count(),
        ];

        return view('admin.users', compact('landlords', 'tenants', 'admins', 'stats'));
    }

    /**
     * Show all tenants.
     */
    public function tenants(Request $request): View
    {
        $query = User::where('role', 'tenant');

        // Search filter
        if ($request->filled('tenant_search')) {
            $searchTerm = $request->tenant_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $tenants = $query->orderBy('name', 'asc')->paginate(20);
        return view('admin.tenants', compact('tenants'));
    }

    /**
     * Show all landlords.
     */
    public function landlords(Request $request): View
    {
        $query = User::where('role', 'landlord')
            ->withCount('listings');

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $query->where('verification_status', $request->status_filter);
        }

        // Properties filter
        if ($request->filled('properties_filter')) {
            $filter = $request->properties_filter;
            if ($filter === '0') {
                $query->having('listings_count', '=', 0);
            } elseif ($filter === '1-5') {
                $query->having('listings_count', '>=', 1)->having('listings_count', '<=', 5);
            } elseif ($filter === '6+') {
                $query->having('listings_count', '>=', 6);
            }
        }

        $landlords = $query->orderBy('name', 'asc')->paginate(20);
        return view('admin.landlords', compact('landlords'));
    }

    /**
     * Show properties management.
     */
    public function properties(): View
    {
        $properties = Listing::with(['user'])
            ->latest()
            ->paginate(20);
        return view('admin.properties', compact('properties'));
    }

    /**
     * Show all listings.
     */
    public function listings(Request $request): View
    {
        $query = Listing::with(['user', 'tenant'])
            ->where(function ($query) {
                $query->where('status', '!=', 'rented')
                      ->orWhereNotNull('tenant_id');
            });

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $listings = $query->latest()->paginate(20);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Show active listings.
     */
    public function activeListings(): View
    {
        $listings = Listing::with(['user'])
            ->where('status', 'active')
            ->latest()
            ->paginate(20);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Show rented properties.
     */
    public function rentedProperties(): View
    {
        $listings = Listing::with(['user', 'tenant'])
            ->where('status', 'rented')
            ->whereNotNull('tenant_id')
            ->latest()
            ->paginate(20);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Show banned listings.
     */
    public function bannedListings(): View
    {
        $listings = Listing::with(['user'])
            ->where('status', 'banned')
            ->latest()
            ->paginate(20);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Delete a listing.
     */
    public function deleteListing(Listing $listing): RedirectResponse
    {
        $listing->delete();
        return redirect()->route('admin.listings')->with('success', 'Listing deleted successfully.');
    }

    /**
     * Show landlords pending verification.
     */
    public function verifyLandlords(): View
    {
        $landlords = User::where('role', 'landlord')
            ->where(function($query) {
                $query->where('verification_status', 'pending')
                      ->orWhereNull('verification_status');
            })
            ->latest()
            ->paginate(20);

        return view('admin.verify-landlords-enhanced', compact('landlords'));
    }

    /**
     * Show tenants pending verification.
     */
    public function verifyTenants(): View
    {
        $tenants = User::where('role', 'tenant')
            ->where(function($query) {
                $query->where('verification_status', 'pending')
                      ->orWhereNull('verification_status');
            })
            ->latest()
            ->paginate(20);

        return view('admin.verify-tenants', compact('tenants'));
    }

    /**
     * Approve user verification using VerificationService.
     */
    public function approveVerification(Request $request, User $user)
    {
        try {
            $result = $this->verificationService->approveVerification($user, Auth::user(), null);

            if (!$result) {
                throw new \Exception('Verification service failed to approve verification');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($user->role) . ' verification approved successfully.'
                ]);
            }

            return back()->with('success', ucfirst($user->role) . ' verification approved successfully.');

        } catch (\Exception $e) {
            Log::error('Verification approval failed', [
                'user_id' => $user ? $user->getKey() : null,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while approving verification. Please try again.'
                ], 500);
            }

            return back()->with('error', 'An error occurred while approving verification. Please try again.');
        }
    }

    /**
     * Reject user verification using VerificationService.
     */
    public function rejectVerification(Request $request, User $user)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:500'
            ]);

            $result = $this->verificationService->rejectVerification($user, Auth::user(), $request->notes ?? '');

            if (!$result) {
                throw new \Exception('Verification service failed to reject verification');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($user->role) . ' verification rejected.'
                ]);
            }

            return back()->with('success', ucfirst($user->role) . ' verification rejected.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Verification rejection failed', [
                'user_id' => $user ? $user->getKey() : null,
                'admin_id' => Auth::user() ? Auth::user()->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while rejecting verification. Please try again.'
                ], 500);
            }

            return back()->with('error', 'An error occurred while rejecting verification. Please try again.');
        }
    }

    /**
     * Export reports functionality.
     */
    public function exportReports()
    {
        // Get all necessary data for export
        $users = User::all();
        $listings = Listing::all();

        // Create CSV content
        $csvContent = "Report Type,Total Count\n";
        $csvContent .= "Total Users," . $users->count() . "\n";
        $csvContent .= "Total Properties," . $listings->count() . "\n";
        $csvContent .= "Active Properties," . $listings->where('status', 'active')->count() . "\n";
        $csvContent .= "Rented Properties," . $listings->where('status', 'rented')->count() . "\n";

        // Additional user breakdown
        $csvContent .= "\nUser Breakdown\n";
        $csvContent .= "Role,Count\n";
        $csvContent .= "Tenants," . $users->where('role', 'tenant')->count() . "\n";
        $csvContent .= "Landlords," . $users->where('role', 'landlord')->count() . "\n";
        $csvContent .= "Admins," . $users->where('role', 'admin')->count() . "\n";

        // Set headers for download
        $filename = 'admin-reports-' . now()->format('Y-m-d-His') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show individual landlord details.
     */
    public function showLandlord(User $landlord): View
    {
        // Ensure the user is actually a landlord
        if ($landlord->role !== 'landlord') {
            abort(404);
        }

        $landlord->load(['listings' => function ($query) {
            $query->latest();
        }]);

        // Get payment history for landlord's properties
        $payments = \App\Models\Payment::with(['tenant', 'listing'])
            ->whereHas('listing', function ($query) use ($landlord) {
                $query->where('user_id', $landlord->id);
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get maintenance requests/issues for landlord's properties
        $issues = \App\Models\Issue::with(['listing', 'tenant'])
            ->whereHas('listing', function ($query) use ($landlord) {
                $query->where('user_id', $landlord->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_properties' => $landlord->listings->count(),
            'active_properties' => $landlord->listings->where('status', 'active')->count(),
            'rented_properties' => $landlord->listings->where('status', 'rented')->count(),
            'total_payments' => $payments->count(),
            'total_payment_amount' => $payments->sum('amount'),
            'pending_issues' => $issues->where('status', 'pending')->count(),
            'total_issues' => $issues->count(),
        ];

        return view('admin.landlord-details', compact('landlord', 'payments', 'issues', 'stats'));
    }

    /**
     * Delete a landlord account.
     */
    public function destroyLandlord(User $landlord): RedirectResponse
    {
        // Ensure the user is actually a landlord
        if ($landlord->role !== 'landlord') {
            abort(404);
        }

        // Soft delete the landlord
        $landlord->delete();

        return redirect()->route('admin.landlords')->with('success', 'Landlord deleted successfully.');
    }

    /**
     * Show individual tenant details.
     */
    public function showTenant(User $tenant): View
    {
        // Ensure the user is actually a tenant
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        $tenant->load([
            'rentalApplications' => function ($query) {
                $query->with('listing.user')->latest();
            }
        ]);

        // Get payment history
        $payments = \App\Models\Payment::with(['listing.user'])
            ->where('tenant_id', $tenant->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get maintenance requests/issues
        $issues = \App\Models\Issue::with(['listing'])
            ->where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_payments' => $payments->count(),
            'total_payment_amount' => $payments->sum('amount'),
            'active_rentals' => $tenant->rentalApplications->where('status', 'approved')->count(),
            'pending_applications' => $tenant->rentalApplications->where('status', 'pending')->count(),
            'maintenance_requests' => $issues->count(),
            'pending_issues' => $issues->where('status', 'pending')->count(),
        ];

        return view('admin.tenant-details', compact('tenant', 'payments', 'issues', 'stats'));
    }

    /**
     * Delete a tenant account.
     */
    public function destroyTenant(User $tenant): RedirectResponse
    {
        // Ensure the user is actually a tenant
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Soft delete the tenant
        $tenant->delete();

        return redirect()->route('admin.tenants')->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Ban a landlord.
     */
    public function banLandlord(User $landlord): RedirectResponse
    {
        // Ensure the user is actually a landlord
        if ($landlord->role !== 'landlord') {
            abort(404);
        }

        // Store the original verification status in notes before banning
        $originalStatus = $landlord->verification_status ?? 'pending';
        $landlord->update([
            'verification_status' => 'banned',
            'verification_notes' => 'Account is banned by administrator|' . $originalStatus . '|' . ($landlord->verification_notes ?? '')
        ]);

        // Ensure the landlord's listings remain visible to tenants (set to active if not rented)
        $landlord->listings()->where('status', '!=', 'rented')->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Landlord banned successfully.');
    }

    /**
     * Unban a landlord.
     */
    public function unbanLandlord(User $landlord): RedirectResponse
    {
        // Ensure the user is actually a landlord
        if ($landlord->role !== 'landlord') {
            abort(404);
        }

        // Restore the original verification status from notes
        $originalStatus = 'pending'; // default fallback
        $originalNotes = '';

        if ($landlord->verification_notes && str_starts_with($landlord->verification_notes, 'Account is banned by administrator|')) {
            $parts = explode('|', $landlord->verification_notes, 3);
            if (count($parts) >= 2) {
                $originalStatus = $parts[1];
                $originalNotes = $parts[2] ?? '';
            }
        }

        $landlord->update([
            'verification_status' => $originalStatus,
            'verification_notes' => $originalNotes ?: null
        ]);

        return redirect()->back()->with('success', 'Landlord unbanned successfully.');
    }

    /**
     * Ban a tenant.
     */
    public function banTenant(User $tenant): RedirectResponse
    {
        // Ensure the user is actually a tenant
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Store the original verification status in notes before banning
        $originalStatus = $tenant->verification_status ?? 'pending';
        $tenant->update([
            'verification_status' => 'banned',
            'verification_notes' => 'Account is banned by administrator|' . $originalStatus . '|' . ($tenant->verification_notes ?? '')
        ]);

        return redirect()->back()->with('success', 'Tenant banned successfully.');
    }

    /**
     * Unban a tenant.
     */
    public function unbanTenant(User $tenant): RedirectResponse
    {
        // Ensure the user is actually a tenant
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Restore the original verification status from notes
        $originalStatus = 'pending'; // default fallback
        $originalNotes = '';

        if ($tenant->verification_notes && str_starts_with($tenant->verification_notes, 'Account is banned by administrator|')) {
            $parts = explode('|', $tenant->verification_notes, 3);
            if (count($parts) >= 2) {
                $originalStatus = $parts[1];
                $originalNotes = $parts[2] ?? '';
            }
        }

        $tenant->update([
            'verification_status' => $originalStatus,
            'verification_notes' => $originalNotes ?: null
        ]);

        return redirect()->back()->with('success', 'Tenant unbanned successfully.');
    }

    /**
     * Show the form for creating a new admin.
     */
    public function createAdmin(): View
    {
        return view('admin.create-admin');
    }

    /**
     * Store a newly created admin.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        // Only main admin can create new admin accounts
        if (strtolower(Auth::user()->email) !== 'main.garalde@gmail.com') {
            return redirect()->route('admin.users')->with('error', 'Only the main admin can create new admin accounts.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'verification_status' => 'approved', // Admins are auto-verified
        ]);

        return redirect()->route('admin.users')->with('success', 'Admin created successfully.');
    }

    /**
     * Show edit form for admin.
     */
    public function editAdmin(User $admin): View
    {
        // Ensure the user is actually an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.edit-admin', compact('admin'));
    }

    /**
     * Update admin details.
     */
    public function updateAdmin(Request $request, User $admin): RedirectResponse
    {
        // Ensure the user is actually an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->getKey(),
            'phone' => 'nullable|string|max:20',
        ];

        // Add password validation rules if password fields are provided
        if ($request->filled('password') || $request->filled('current_password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required|string|min:8';
        }

        $request->validate($rules);

        // Check current password if changing password
        if ($request->filled('password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }
        }

        $updateData = $request->only(['name', 'email', 'phone']);

        // Add password to update data if provided
        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Admin updated successfully.');
    }

    /**
     * Delete an admin account.
     */
    public function destroyAdmin(User $admin): RedirectResponse
    {
        // Ensure the user is actually an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        // Only main admin can delete other admins
        if (strtolower(Auth::user()->email) !== 'main.garalde@gmail.com') {
            return back()->with('error', 'Only the main admin can delete admin accounts.');
        }

        // Prevent deletion of the current admin user
        if ($admin->getKey() === Auth::user()->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        // Prevent deletion of the main admin account (main.garalde@gmail.com)
        if (strtolower($admin->email) === 'main.garalde@gmail.com') {
            return back()->with('error', 'Cannot delete the main admin account.');
        }

        // Prevent deleting if this is the only admin
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return back()->with('error', 'Cannot delete the last admin account.');
        }

        // Soft delete the admin
        $admin->delete();

        return redirect()->route('admin.users')->with('success', 'Admin deleted successfully.');
    }

    /**
     * Get user verification details for modal display.
     */
    public function getUserVerificationDetails(User $user)
    {
        // Ensure the user exists and is a landlord or tenant
        if (!$user->exists || !in_array($user->role, ['landlord', 'tenant'])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or invalid user type.'
            ], 404);
        }

        // Handle separate front and back image paths
        $validIdFrontUrl = null;
        $validIdBackUrl = null;

        if ($user->valid_id_path) {
            $validIdFrontUrl = url('image-server.php?path=' . urlencode($user->valid_id_path));
        }

        if ($user->valid_id_back_path) {
            $validIdBackUrl = url('image-server.php?path=' . urlencode($user->valid_id_back_path));
        }

        // Prepare verification data - use name field if available, otherwise construct from first_name and surname
        $userName = $user->name;
        if (!$userName && ($user->first_name || $user->surname)) {
            $userName = trim(($user->first_name ?? '') . ' ' . ($user->surname ?? ''));
        }
        if (!$userName) {
            $userName = 'N/A';
        }

        // Prepare verification data
        $verificationData = [
            'user_id' => $user->getKey(),
            'name' => $userName,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'verification_status' => $user->verification_status ?? 'pending',
            'document_type' => $user->document_type,
            'verification_notes' => $user->verification_notes,
            'created_at' => $user->created_at->format('M d, Y'),
            'front_image_url' => $validIdFrontUrl,
            'back_image_url' => $validIdBackUrl,
        ];

        return response()->json([
            'success' => true,
            'data' => $verificationData
        ]);
    }

    /**
     * Show individual listing details for admin.
     */
    public function showListing(Listing $listing): View
    {
        $listing->load(['user']);

        // Get favorite and like data for the listing (similar to tenant view)
        $favoriteListingIds = [];
        $likedListingIds = [];
        $likeCounts = [];

        return view('admin.listing-details', compact('listing', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
    }

    /**
     * Ban a listing.
     */
    public function banListing(Listing $listing): RedirectResponse
    {
        // Update listing status to banned
        $listing->update([
            'status' => 'banned'
        ]);

        return redirect()->back()->with('success', 'Listing banned successfully.');
    }

    /**
     * Unban a listing.
     */
    public function unbanListing(Listing $listing): RedirectResponse
    {
        // Update listing status to active
        $listing->update([
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Listing unbanned successfully.');
    }

    /**
     * Show all payments (admin view).
     */
    public function paymentHistory(Request $request)
    {
        $query = \App\Models\Payment::with(['tenant', 'listing.user']);

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

        // Landlord filter
        if ($request->filled('landlord_filter') && $request->landlord_filter !== 'all') {
            $query->whereHas('listing', function ($listingQuery) use ($request) {
                $listingQuery->where('user_id', $request->landlord_filter);
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        return view('admin.payment-history', compact('payments'));
    }

    /**
     * Export payments to CSV.
     */
    public function exportPayments(Request $request)
    {
        $query = \App\Models\Payment::with(['tenant', 'listing.user']);

        // Apply same filters as paymentHistory
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

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        if ($request->filled('method_filter') && $request->method_filter !== 'all') {
            $query->where('payment_method', $request->method_filter);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        // Create CSV content
        $csvContent = "Payment ID,Tenant Name,Tenant Email,Landlord Name,Landlord Email,Property Title,Amount,Payment Method,Status,Payment Date\n";

        foreach ($payments as $payment) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $payment->id,
                $payment->tenant ? $payment->tenant->name : 'N/A',
                $payment->tenant ? $payment->tenant->email : 'N/A',
                $payment->listing && $payment->listing->user ? $payment->listing->user->name : 'N/A',
                $payment->listing && $payment->listing->user ? $payment->listing->user->email : 'N/A',
                $payment->listing ? $payment->listing->title : 'N/A',
                $payment->amount,
                $payment->payment_method,
                $payment->status,
                $payment->payment_date ? $payment->payment_date->format('Y-m-d H:i:s') : 'N/A'
            );
        }

        // Set headers for download
        $filename = 'payments-export-' . now()->format('Y-m-d-His') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get payment details for modal display.
     */
    public function getPaymentDetails(\App\Models\Payment $payment)
    {
        $payment->load(['tenant', 'listing.user']);

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : null,
                'is_on_time' => $payment->is_on_time,
                'verified_at' => $payment->verified_at ? $payment->verified_at->format('M d, Y H:i') : null,
                'rejected_at' => $payment->rejected_at ? $payment->rejected_at->format('M d, Y H:i') : null,
                'rejection_reason' => $payment->rejection_reason,
                'notes' => $payment->notes,
                'receipt_url' => $payment->receipt_url,
                'transaction_details' => $payment->transaction_details,
                'verifier' => $payment->verifier,
                'rejecter' => $payment->rejecter,
                'tenant' => $payment->tenant ? [
                    'name' => $payment->tenant->name,
                    'email' => $payment->tenant->email,
                    'phone' => $payment->tenant->phone,
                ] : null,
                'listing' => $payment->listing ? [
                    'title' => $payment->listing->title,
                    'location' => $payment->listing->location,
                    'user' => $payment->listing->user ? [
                        'name' => $payment->listing->user->name,
                        'email' => $payment->listing->user->email,
                    ] : null,
                ] : null,
            ]
        ]);
    }

    /**
     * Verify a payment (admin action).
     */
    public function verifyPayment(\App\Models\Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not in pending status.'
            ], 400);
        }

        $payment->update([
            'status' => 'completed',
            'verified_at' => now(),
            'verifier' => Auth::user()->name,
        ]);

        // Send notification to tenant
        if ($payment->tenant) {
            $payment->tenant->notify(new \App\Notifications\PaymentVerifiedNotification($payment));
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.'
        ]);
    }

    /**
     * Reject a payment (admin action).
     */
    public function rejectPayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not in pending status.'
            ], 400);
        }

        $payment->update([
            'status' => 'failed',
            'rejected_at' => now(),
            'rejection_reason' => $request->reason,
            'rejecter' => Auth::user()->name,
        ]);

        // Send notification to tenant
        if ($payment->tenant) {
            $payment->tenant->notify(new \App\Notifications\PaymentRejectedNotification($payment));
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment rejected successfully.'
        ]);
    }

    /**
     * Refund a payment (admin action).
     */
    public function refundPayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if (!in_array($payment->status, ['completed', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment cannot be refunded in its current status.'
            ], 400);
        }

        $payment->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_reason' => $request->reason,
            'refunder' => Auth::user()->name,
        ]);

        // Send notification to tenant
        if ($payment->tenant) {
            $payment->tenant->notify(new \App\Notifications\PaymentRefundedNotification($payment));
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully.'
        ]);
    }

    /**
     * Dispute a payment (admin action).
     */
    public function disputePayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed payments can be disputed.'
            ], 400);
        }

        $payment->update([
            'status' => 'disputed',
            'disputed_at' => now(),
            'dispute_reason' => $request->reason,
            'disputer' => Auth::user()->name,
        ]);

        // Send notification to tenant
        if ($payment->tenant) {
            $payment->tenant->notify(new \App\Notifications\PaymentDisputedNotification($payment));
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment disputed successfully.'
        ]);
    }

    /**
     * Remove a payment (admin action).
     */
    public function removePayment(\App\Models\Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment removed successfully.'
        ]);
    }

    /**
     * Show analytics dashboard.
     */
    public function analytics(): View
    {
        // Property statistics
        $totalListings = \App\Models\Listing::count();
        $activeListings = \App\Models\Listing::where('status', 'active')->count();
        $rentedListings = \App\Models\Listing::where('status', 'rented')->count();
        $totalRevenue = \App\Models\Listing::where('status', 'rented')->sum('price');

        // Reports statistics
        $reportStats = [
            'total_reports' => \App\Models\Report::count(),
            'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
            'reviewed_reports' => \App\Models\Report::where('status', 'reviewed')->count(),
            'resolved_reports' => \App\Models\Report::where('status', 'resolved')->count(),
            'recent_reports' => \App\Models\Report::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // User statistics
        $totalUsers = \App\Models\User::count();
        $totalTenants = \App\Models\User::where('role', 'tenant')->count();
        $totalLandlords = \App\Models\User::where('role', 'landlord')->count();
        $activePosts = \App\Models\Listing::where('status', 'active')->count();

        return view('admin.analytics', compact(
            'totalListings',
            'activeListings',
            'rentedListings',
            'totalRevenue',
            'reportStats',
            'totalUsers',
            'totalTenants',
            'totalLandlords',
            'activePosts'
        ));
    }
}
