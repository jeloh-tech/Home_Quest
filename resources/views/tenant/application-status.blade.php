@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden"
     x-data="favoritesManager()"
     x-init="init()"
     data-favorite-ids="{{ json_encode($favoriteListingIds ?? []) }}"
     data-liked-ids="{{ json_encode($likedListingIds ?? []) }}"
     data-like-counts="{{ json_encode($likeCounts ?? []) }}">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Enhanced Header Section -->
            <div class="mb-8">
                <!-- Property Owner & Actions Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-8 border-l-4 border-blue-500 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            @if($listing->user)
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full overflow-hidden flex items-center justify-center mr-4 border-4 border-white shadow-lg">
                                    @if($listing->user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $listing->user->profile_photo_path) }}" alt="Profile Picture" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-gray-900 dark:text-white">{{ $listing->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Property Owner</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-gray-900 dark:text-white">Unknown Owner</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Property Owner</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons - Centered -->
                        <div class="flex items-center space-x-4">
                            @if($existingApplication->status === 'pending' && !$existingApplication->cancelled_at)
                            <a href="{{ route('tenant.rental-application.edit', $existingApplication->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Application
                            </a>
                            @elseif($existingApplication->status === 'cancelled' && $existingApplication->cancelled_at && $existingApplication->cancelled_at->addHour()->isFuture())
                            <form method="POST" action="{{ route('tenant.rental-application.undo-cancel', $existingApplication->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Undo Cancel ({{ $existingApplication->cancelled_at->addHour()->diffForHumans() }})
                                </button>
                            </form>
                            @endif
                            @if($listing->user)
                            <a href="{{ route('tenant.user.show', $listing->user->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Visit Account
                            </a>
                            @endif
                            <button id="reportBtn" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Report
                            </button>
                        </div>

                        <div class="text-right">
                            <div class="bg-green-500 text-white px-6 py-3 rounded-lg">
                                <p class="text-4xl font-bold">₱{{ number_format($listing->price, 2) }}</p>
                                <p class="text-sm opacity-90">per month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Status Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 mb-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Application Status</h2>
                    @if($existingApplication->status === 'pending' && !$existingApplication->cancelled_at)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-6">
                        <p class="text-xl font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Your application is under review</p>
                        <p class="text-gray-600 dark:text-gray-400">Your rental application has been submitted and is currently under review by the landlord. You will be notified once a decision is made.</p>
                    </div>
                    @elseif($existingApplication->status === 'cancelled')
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 mb-6">
                        <p class="text-xl font-semibold text-red-800 dark:text-red-200 mb-2">Application Cancelled</p>
                        @if($existingApplication->cancelled_at && $existingApplication->cancelled_at->addHour()->isFuture())
                        <p class="text-gray-600 dark:text-gray-400">Your application has been cancelled. You have until {{ $existingApplication->cancelled_at->addHour()->format('M d, Y H:i') }} to undo this action.</p>
                        @else
                        <p class="text-gray-600 dark:text-gray-400">Your application has been cancelled and the undo window has expired.</p>
                        @endif
                    </div>
                    @elseif($existingApplication->status === 'accepted' && $listing->tenant_id === auth()->id())
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 mb-6">
                        <p class="text-xl font-semibold text-green-800 dark:text-green-200 mb-2">Application Accepted!</p>
                        <p class="text-gray-600 dark:text-gray-400">Congratulations! Your rental application has been accepted. The landlord will contact you soon with next steps.</p>
                    </div>
                    @elseif($existingApplication->status === 'rejected')
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 mb-6">
                        <p class="text-xl font-semibold text-red-800 dark:text-red-200 mb-2">Application Rejected</p>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Unfortunately, your rental application has been rejected. You may apply for other properties or contact the landlord for more information.</p>
                            <a href="{{ route('tenant.rental-application.show', $listing->id) }}?reapply=1" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center inline-flex">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </a>
                    </div>
                    @endif
                </div>

                <!-- Application Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Application Details</h3>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Application ID:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">#{{ $existingApplication->id }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Submitted:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $existingApplication->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($existingApplication->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($existingApplication->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($existingApplication->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($existingApplication->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($existingApplication->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Move-in Date:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($existingApplication->planned_move_in_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">End Date:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($existingApplication->planned_end_date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Personal Information</h3>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Full Name:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $existingApplication->full_name }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Phone:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $existingApplication->phone }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $existingApplication->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Employment:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($existingApplication->employment_status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($existingApplication->reason_for_moving || $existingApplication->additional_notes || ($existingApplication->status === 'pending' && !$existingApplication->cancelled_at))
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Additional Information</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        @if($existingApplication->reason_for_moving)
                        <div class="mb-4">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Reason for Moving:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $existingApplication->reason_for_moving }}</p>
                        </div>
                        @endif

                        @if($existingApplication->status === 'pending' && !$existingApplication->cancelled_at)
                        <div class="mb-4 flex justify-end">
                            <form method="POST" action="{{ route('tenant.rental-application.cancel', $existingApplication->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center" onclick="return confirm('Are you sure you want to cancel this application? You will have 1 hour to undo this action.')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel Application
                                </button>
                            </form>
                        </div>
                        @endif

                        @if($existingApplication->additional_notes)
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Additional Notes:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $existingApplication->additional_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Property Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Property Features -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Property Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Bedrooms</p>
                                <p class="font-bold text-xl text-gray-900 dark:text-white">{{ $listing->room_count ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Bathrooms</p>
                                <p class="font-bold text-xl text-gray-900 dark:text-white">{{ $listing->bathroom_count ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Property Type</p>
                                <p class="font-bold text-xl text-gray-900 dark:text-white">{{ $listing->property_type ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Area</p>
                                <p class="font-bold text-xl text-gray-900 dark:text-white">{{ $listing->area ?? 'N/A' }} sqm</p>
                            </div>
                        </div>
                    </div>

                    <!-- Property Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Description</h2>
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                                {{ $listing->description ?? 'No description available for this property.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Contact Landlord -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Contact Landlord</h2>
                        @if($listing->user)
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-4">
                                    @if($listing->user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $listing->user->profile_photo_path) }}" alt="Profile Picture" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $listing->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Property Owner</p>
                                </div>
                            </div>
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Send Message
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Property Images -->
                    @if($listing->images && count($listing->images) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Property Images</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($listing->images as $image)
                            <div class="aspect-square rounded-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $image) }}" alt="Property Image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
