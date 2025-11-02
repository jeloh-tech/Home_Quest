 <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Landlord\DashboardController as LandlordDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/listings', [TenantDashboardController::class, 'publicListings'])->name('listings');
Route::get('/property-details/{id}', [TenantDashboardController::class, 'publicPropertyDetails'])->name('property-details');
Route::get('/user/{id}', [TenantDashboardController::class, 'showUser'])->name('user.show.public');

// Public routes for likes and favorites (redirect to login for unauthenticated users)
Route::post('/favorites/toggle/{listing}', function () {
    return response()->json([
        'success' => false,
        'message' => 'Please log in to favorite properties.',
        'redirect' => '/login'
    ], 401);
})->name('favorites.toggle.public');

Route::post('/likes/toggle/{listing}', function () {
    return response()->json([
        'success' => false,
        'message' => 'Please log in to like properties.',
        'redirect' => '/login'
    ], 401);
})->name('likes.toggle.public');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user && $user->role === 'landlord') {
        return redirect()->route('landlord.dashboard');
    }
    if ($user && $user->role === 'tenant') {
        return redirect()->route('tenant.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['web', 'auth', 'verified', 'check.banned', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [TenantDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [TenantDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [TenantDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/my-rental', [TenantDashboardController::class, 'myRental'])->name('rental');
    Route::get('/lease', [TenantDashboardController::class, 'viewLease'])->name('lease');
    Route::get('/pay-rent', [TenantDashboardController::class, 'showPayRent'])->name('pay-rent.show');
    Route::post('/pay-rent', [TenantDashboardController::class, 'payRent'])->name('pay-rent');
    Route::get('/report-issue', [TenantDashboardController::class, 'showReportIssue'])->name('report-issue.show');
    Route::post('/report-issue', [TenantDashboardController::class, 'reportIssue'])->name('report-issue');
    Route::get('/property-search', [TenantDashboardController::class, 'propertySearch'])->name('search');
    Route::get('/favorites', [TenantDashboardController::class, 'favorites'])->name('favorites');
    Route::post('/favorites/toggle/{listing}', [TenantDashboardController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/likes/toggle/{listing}', [TenantDashboardController::class, 'toggleLike'])->name('likes.toggle');
    Route::get('/property-map', [TenantDashboardController::class, 'propertyMap'])->name('map');
    Route::get('/advanced-search', [TenantDashboardController::class, 'advancedSearch'])->name('advanced-search');
    Route::get('/property-details/{id}', [TenantDashboardController::class, 'propertyDetails'])->name('property-details');
    Route::get('/user/{id}', [TenantDashboardController::class, 'showUser'])->name('user.show');
    Route::get('/messages', [TenantDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{userId}', [TenantDashboardController::class, 'showConversation'])->name('messages.conversation');
    Route::get('/messages/{userId}/ajax', [TenantDashboardController::class, 'getConversationAjax'])->name('messages.conversation.ajax');
    Route::post('/messages/{receiverId}', [TenantDashboardController::class, 'sendMessage'])->name('messages.send');
    Route::get('/applications', [TenantDashboardController::class, 'myApplications'])->name('applications');
    Route::get('/rental-application/{listing}', [TenantDashboardController::class, 'showRentalApplication'])->name('rental-application.show');
    Route::post('/rental-application/{listing}', [TenantDashboardController::class, 'storeRentalApplication'])->name('rental-application.store');
    Route::get('/rental-application/{application}/edit', [TenantDashboardController::class, 'editRentalApplication'])->name('rental-application.edit');
    Route::put('/rental-application/{application}', [TenantDashboardController::class, 'updateRentalApplication'])->name('rental-application.update');
    Route::post('/rental-application/{application}/cancel', [TenantDashboardController::class, 'cancelRentalApplication'])->name('rental-application.cancel');
    Route::post('/rental-application/{application}/undo-cancel', [TenantDashboardController::class, 'undoCancelRentalApplication'])->name('rental-application.undo-cancel');
    Route::post('/leave-rental', [TenantDashboardController::class, 'leaveRental'])->name('leave-rental');
});

// Public routes for likes (no auth required)
Route::post('/likes/toggle/{listing}', [TenantDashboardController::class, 'toggleLike'])->name('likes.toggle');

// Reports (authenticated users only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/reports', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
});

Route::middleware(['web', 'auth', 'verified', 'check.banned', 'role:landlord'])->prefix('landlord')->name('landlord.')->group(function () {
    Route::get('/dashboard', [LandlordDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [LandlordDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [LandlordDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [LandlordDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/add-post', [LandlordDashboardController::class, 'addPost'])->name('add-post');
    Route::post('/add-post', [LandlordDashboardController::class, 'storeListing'])->name('add-post.store');
    Route::get('/verify', [LandlordDashboardController::class, 'verify'])->name('verify');
    Route::post('/submit-verification', [LandlordDashboardController::class, 'submitVerification'])->name('submitVerification');
    Route::post('/upload-verification-documents', [LandlordDashboardController::class, 'uploadVerificationDocuments'])->name('upload-verification-documents');
    Route::get('/verification-data', [LandlordDashboardController::class, 'getVerificationData'])->name('verification-data');

    Route::get('/properties', [LandlordDashboardController::class, 'properties'])->name('properties');
    Route::get('/properties/{id}/edit', [LandlordDashboardController::class, 'editListing'])->name('properties.edit');
    Route::put('/properties/{id}', [LandlordDashboardController::class, 'updateListing'])->name('properties.update');
    Route::delete('/properties/{id}', [LandlordDashboardController::class, 'deleteListing'])->name('properties.delete');
    Route::get('/properties/{listing}/applications', [LandlordDashboardController::class, 'showApplications'])->name('properties.applications');
    Route::post('/applications/{application}/accept', [LandlordDashboardController::class, 'acceptApplication'])->name('applications.accept');
    Route::post('/applications/{application}/reject', [LandlordDashboardController::class, 'rejectApplication'])->name('applications.reject');
    Route::post('/applications/{application}/terminate', [LandlordDashboardController::class, 'terminateRental'])->name('applications.terminate');
    Route::delete('/applications/{application}/remove', [LandlordDashboardController::class, 'removeApplication'])->name('applications.remove');
    Route::get('/messages', [LandlordDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{userId}', [LandlordDashboardController::class, 'showConversation'])->name('messages.conversation');
    Route::get('/messages/{userId}/ajax', [LandlordDashboardController::class, 'getConversationAjax'])->name('messages.conversation.ajax');
    Route::post('/messages/{receiverId}', [LandlordDashboardController::class, 'sendMessage'])->name('messages.send');
    Route::get('/payment-history', [LandlordDashboardController::class, 'paymentHistory'])->name('payment-history');
    Route::get('/pending-verifications', [LandlordDashboardController::class, 'pendingVerifications'])->name('pending-verifications');
    Route::post('/payments/{payment}/verify', [LandlordDashboardController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [LandlordDashboardController::class, 'rejectPayment'])->name('payments.reject');
    Route::get('/payments/{payment}/details', [LandlordDashboardController::class, 'getPaymentDetails'])->name('payments.details');
    Route::get('/maintenance-requests', [LandlordDashboardController::class, 'maintenanceRequests'])->name('maintenance-requests');
    Route::get('/issues/{issue}/details', [LandlordDashboardController::class, 'getIssueDetails'])->name('issues.details');
    Route::post('/issues/{issue}/accept', [LandlordDashboardController::class, 'acceptIssue'])->name('issues.accept');
    Route::post('/issues/{issue}/resolve', [LandlordDashboardController::class, 'resolveIssue'])->name('issues.resolve');
    Route::post('/issues/{issue}/message', [LandlordDashboardController::class, 'messageTenant'])->name('issues.message');
    Route::post('/logout', [LandlordDashboardController::class, 'logout'])->name('logout');
    Route::get('/verification-success', function () {
        return view('landlord.verification-success');
    })->name('verification.success');
});

Route::middleware(['auth', 'check.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'users'])->name('users');
    Route::get('/tenants', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'tenants'])->name('tenants');
    Route::get('/landlords', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'landlords'])->name('landlords');

    // Properties and Listings Management
    Route::get('/properties', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'properties'])->name('properties');
    Route::get('/listings', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'listings'])->name('listings');
    Route::get('/listings/active', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'activeListings'])->name('listings.active');
    Route::get('/listings/rented', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'rentedProperties'])->name('listings.rented');
    Route::get('/listings/banned', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'bannedListings'])->name('listings.banned');
    Route::get('/listings/{listing}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'showListing'])->name('listings.show');
    Route::post('/listings/{listing}/ban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'banListing'])->name('listings.ban');
    Route::post('/listings/{listing}/unban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'unbanListing'])->name('listings.unban');
    Route::delete('/listings/{listing}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'deleteListing'])->name('listings.delete');

    // Verification Management
    Route::get('/verification/landlords', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'verifyLandlords'])->name('verification.landlords');
    Route::get('/verification/tenants', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'verifyTenants'])->name('verification.tenants');
    Route::post('/verification/approve/{user}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'approveVerification'])->name('verification.approve');
    Route::post('/verification/reject/{user}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectVerification'])->name('verification.reject');

    // Landlord Management
    Route::get('/landlords/{landlord}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'showLandlord'])->name('landlords.show');
    Route::delete('/landlords/{landlord}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroyLandlord'])->name('landlords.destroy');
    Route::patch('/landlords/{landlord}/ban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'banLandlord'])->name('landlords.ban');
    Route::patch('/landlords/{landlord}/unban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'unbanLandlord'])->name('landlords.unban');

    // Tenant Management
    Route::get('/tenants/{tenant}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'showTenant'])->name('tenants.show');
    Route::delete('/tenants/{tenant}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroyTenant'])->name('tenants.destroy');
    Route::patch('/tenants/{tenant}/ban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'banTenant'])->name('tenants.ban');
    Route::patch('/tenants/{tenant}/unban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'unbanTenant'])->name('tenants.unban');

    // Admin Management - Create, Edit and Delete only (no ban/unban as per user request)
    Route::get('/admins/create', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'storeAdmin'])->name('admins.store');
    Route::get('/admins/{admin}/edit', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/{admin}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{admin}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroyAdmin'])->name('admins.destroy');

    // User verification details API
    Route::get('/users/{user}/verification-details', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'getUserVerificationDetails'])->name('users.verification-details');

    // Verification status API
    Route::get('/verification-status/{userId}', [\App\Http\Controllers\VerificationStatusController::class, 'show'])->name('verification.status');

    // Reports and Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'exportReports'])->name('reports.export');

    // Payment History
    Route::get('/payment-history', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'paymentHistory'])->name('payment-history');
    Route::get('/export-payments', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'exportPayments'])->name('export-payments');
    Route::post('/payments/{payment}/verify', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectPayment'])->name('payments.reject');
    Route::get('/payments/{payment}/details', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'getPaymentDetails'])->name('payments.details');
    Route::post('/payments/{payment}/refund', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'refundPayment'])->name('payments.refund');
    Route::post('/payments/{payment}/dispute', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'disputePayment'])->name('payments.dispute');

    // Reports Management
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [\App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');
    Route::put('/reports/{report}', [\App\Http\Controllers\ReportController::class, 'update'])->name('reports.update');
    Route::get('/reports-statistics', [\App\Http\Controllers\ReportController::class, 'statistics'])->name('reports.statistics');

    // Payment Management
    Route::delete('/payments/{payment}/remove', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'removePayment'])->name('payments.remove');
});

Route::get('admin/login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'logout'])->name('admin.logout');

Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/landlord/logout', [App\Http\Controllers\Landlord\DashboardController::class, 'logout'])->name('landlord.logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Registration success page
Route::get('/register/success', function () {
    $user = session('registered_user');
    if (!$user) {
        return redirect('/');
    }
    return view('auth.register-success', ['user' => $user]);
})->name('register.success');

// Banned user page
Route::get('/banned', function () {
    return view('banned');
})->name('banned');

// Test modal functionality page
Route::get('/test-modal', function () {
    return view('test_modal_functionality');
})->name('test.modal');

// Serve uploaded images
Route::get('/storage/image/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath) || !is_readable($fullPath)) {
        abort(404);
    }

    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
    ];

    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'max-age=3600',
    ]);
})->where('path', '.*');
