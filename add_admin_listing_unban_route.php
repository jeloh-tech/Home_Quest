<?php

// Add unban route for admin listings
Route::post('/listings/{listing}/unban', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'unbanListing'])->name('listings.unban');
