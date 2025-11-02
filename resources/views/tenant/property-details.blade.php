@extends('layouts.app')

@section('content')
<script>
    window.favoriteListingIds = @json($favoriteListingIds ?? []);
    window.likedListingIds = @json($likedListingIds ?? []);
    window.likeCounts = @json($likeCounts ?? []);
    window.isAuthenticated = @json(Auth::check());
</script>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Report Listing</h3>
            <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="reportForm" onsubmit="event.preventDefault(); submitReport();">
                @csrf
                <input type="hidden" name="listing_id" id="report_listing_id">

                <!-- Reason Selection -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for reporting <span class="text-red-500">*</span>
                    </label>
                    <select name="reason" id="reason" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white transition-colors">
                        <option value="">Select a reason</option>
                        <option value="inappropriate_content">Inappropriate content</option>
                        <option value="spam">Spam or misleading</option>
                        <option value="fraudulent">Fraudulent listing</option>
                        <option value="offensive">Offensive language</option>
                        <option value="duplicate">Duplicate listing</option>
                        <option value="wrong_information">Wrong information</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional details <span class="text-gray-500">(optional)</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Please provide any additional details about why you're reporting this listing..."
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white transition-colors resize-vertical"
                              maxlength="1000"></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 1000 characters</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3">
                    <button type="button" onclick="closeReportModal()"
                            class="flex-1 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="submitReportBtn"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                            @if($canApply ?? true)
                            <a href="{{ route('tenant.rental-application.show', $listing->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Apply
                            </a>
                            @else
                            <button disabled class="bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-sm cursor-not-allowed flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Apply (Application Pending)
                            </button>
                            @endif
                            @if($listing->user)
                            <a href="{{ route('tenant.user.show', $listing->user->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Visit Account
                            </a>
                            @endif
                            <button onclick="openReportModal()" data-listing-id="{{ $listing->id }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
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

            <!-- Professional Property Images Gallery -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                @if($listing->images && count($listing->images) > 0)
                <div class="relative max-w-5xl mx-auto">
                    <!-- Main Image Carousel -->
                    <div class="relative overflow-hidden bg-gray-100 dark:bg-gray-900">
                        <div id="mainCarousel" class="relative h-[400px] md:h-[500px] lg:h-[600px]">
                            @foreach($listing->images as $index => $image)
                            <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100' : '' }}"
                                 data-slide="{{ $index }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="Property image {{ $index + 1 }}"
                                     class="w-full h-full object-cover">
                                <!-- Image Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Navigation Arrows -->
                        @if(count($listing->images) > 1)
                        <button id="prevArrow" class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/90 hover:bg-white dark:bg-gray-800/90 dark:hover:bg-gray-800 rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                            <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button id="nextArrow" class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/90 hover:bg-white dark:bg-gray-800/90 dark:hover:bg-gray-800 rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                            <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        @endif

                        <!-- Image Counter & Info -->
                        <div class="absolute top-8 left-8 right-8 flex justify-between items-start z-10">
                            <div class="bg-black/70 backdrop-blur-sm text-white px-4 py-2 rounded-lg">
                                <div class="text-sm font-medium">
                                    <span id="currentSlide">1</span> / {{ count($listing->images) }}
                                </div>
                            </div>
                            <div class="bg-black/70 backdrop-blur-sm text-white px-4 py-2 rounded-lg">
                                <div class="text-sm flex items-center">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $listing->location }}
                                </div>
                            </div>
                        </div>

                        <!-- Zoom Button -->
                        <button id="zoomBtn" class="absolute bottom-8 right-8 w-14 h-14 bg-white/90 hover:bg-white dark:bg-gray-800/90 dark:hover:bg-gray-800 rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                            <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Thumbnail Navigation -->
                    @if(count($listing->images) > 1)
                    <div class="bg-gray-50 dark:bg-gray-900 p-6">
                        <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach($listing->images as $index => $image)
                            <button class="thumbnail-btn flex-shrink-0 w-24 h-20 md:w-28 md:h-24 rounded-lg overflow-hidden border-3 transition-all duration-200 {{ $index === 0 ? 'border-blue-500 shadow-lg scale-105' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}"
                                    data-slide="{{ $index }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="Thumbnail {{ $index + 1 }}"
                                     class="w-full h-full object-cover">
                            </button>
                            @endforeach
                        </div>

                        <!-- Dot Indicators -->
                        <div class="flex justify-center space-x-2 mt-6">
                            @for($i = 0; $i < count($listing->images); $i++)
                            <button class="dot-indicator w-3 h-3 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-blue-500 w-8' : 'bg-gray-300 dark:bg-gray-600' }}"
                                    data-slide="{{ $i }}"></button>
                            @endfor
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <!-- No Images Placeholder -->
                <div class="h-[400px] md:h-[500px] lg:h-[600px] bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-32 h-32 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-2xl font-medium text-gray-500 dark:text-gray-400 mb-3">No Images Available</h3>
                        <p class="text-gray-400">Property images will be displayed here</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Property Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Amenities -->
                    @if($listing->amenities)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <div class="flex flex-wrap gap-3">
                            @php
                                $amenities = is_string($listing->amenities) ? explode(',', $listing->amenities) : (is_array($listing->amenities) ? $listing->amenities : []);
                            @endphp
                            @foreach($amenities as $amenity)
                                <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                    {{ trim($amenity) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

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
                            <a href="{{ route('tenant.messages.conversation', $listing->user->id) }}?listing_id={{ $listing->id }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Send Message
                            </a>
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                </svg>
                                Schedule Viewing
                            </button>
                        </div>
                        @else
                        <p class="text-gray-600 dark:text-gray-400">Landlord information not available.</p>
                        @endif
                    </div>

                    <!-- Property Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Property Status</h2>
                        <div class="flex items-center mb-4">
                            @if($listing->status === 'available')
                                <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-green-600 dark:text-green-400 font-bold text-lg">Available</span>
                            @elseif($listing->status === 'rented')
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-red-600 dark:text-red-400 font-bold text-lg">Rented</span>
                            @else
                                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-yellow-600 dark:text-yellow-400 font-bold text-lg">{{ ucfirst($listing->status ?? 'Unknown') }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Listed {{ $listing->created_at ? $listing->created_at->diffForHumans() : 'recently' }}
                        </p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Quick Stats</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Views</span>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $listing->views ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Favorites</span>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $listing->favorites_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Likes</span>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $listing->likes_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <div class="space-y-4">
                            <!-- Report Button -->
                            <button onclick="openReportModal()" data-listing-id="{{ $listing->id }}" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center block flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Report Listing
                            </button>

                            <!-- Back to Map -->
                            <a href="{{ route('tenant.map') }}" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center block flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Map
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Report modal functions
function openReportModal() {
    const listingId = event.currentTarget.getAttribute('data-listing-id');
    document.getElementById('reportModal').classList.remove('hidden');
    document.getElementById('report_listing_id').value = listingId;
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportForm').reset();
}

function submitReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitReportBtn');
    const originalText = submitBtn.innerHTML;

    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Submitting...
    `;

    fetch('{{ route("reports.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message, 'success');
            closeReportModal();
        } else {
            // Show error message
            showNotification(data.message || 'An error occurred while submitting your report.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while submitting your report. Please try again.', 'error');
    })
    .finally(() => {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500', 'text-white');
    } else {
        notification.classList.add('bg-blue-500', 'text-white');
    }

    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 300);
    }, 5000);
}
</script>
@endsection
