@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <div class="flex-1 min-h-screen bg-gray-50 ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Page Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Listing Details</h1>
                            <p class="text-sm text-gray-600 mt-2">Admin view of property listing</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.listings') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Listings
                            </a>
                        </div>
                    </div>

                    <!-- Property Header Section with Admin Actions -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-8 border-l-4 border-blue-500 mb-8">
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
                                        <p class="font-bold text-xl text-gray-900">{{ $listing->user->name }}</p>
                                        <p class="text-sm text-gray-600">Property Owner</p>
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xl text-gray-900">Unknown Owner</p>
                                        <p class="text-sm text-gray-600">Property Owner</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Admin Actions - Centered with Profile -->
                            <div class="flex items-center space-x-4">
                                @if($listing->status === 'banned')
                                    <form method="POST" action="{{ route('admin.listings.unban', $listing) }}" onsubmit="return confirm('Are you sure you want to unban this listing?')" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Unban Post
                                        </button>
                                    </form>
                                @elseif($listing->status !== 'rented')
                                    <form method="POST" action="{{ route('admin.listings.ban', $listing) }}" onsubmit="return confirm('Are you sure you want to ban this listing?')" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            Ban Post
                                        </button>
                                    </form>
                                @endif
                                @if($listing->status === 'banned')
                                    <form method="POST" action="{{ route('admin.listings.delete', $listing) }}" onsubmit="return confirm('Are you sure you want to delete this listing?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-lg text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Listing
                                        </button>
                                    </form>
                                @elseif($listing->status !== 'rented')
                                    <button class="bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-sm cursor-not-allowed opacity-50 flex items-center" disabled>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete Listing
                                    </button>
                                @endif
                            </div>
+                            @if($listing->status === 'rented')
+                                <div class="inline-block border-2 border-red-500 text-red-600 font-semibold py-3 px-6 rounded-lg text-lg shadow-md uppercase tracking-wider select-none bg-white">
+                                    RENTED THIS POST
+                                </div>
+                            @endif

                            <div class="text-right">
                                <div class="bg-green-500 text-white px-6 py-3 rounded-lg">
                                    <p class="text-4xl font-bold">₱{{ number_format($listing->price, 2) }}</p>
                                    <p class="text-sm opacity-90">per month</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Details Content -->
                    <div class="space-y-8">
                        <!-- Property Images Gallery -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            @if($listing->images && count($listing->images) > 0)
                            <div class="relative max-w-5xl mx-auto">
                                <!-- Main Image Carousel -->
                                <div class="relative overflow-hidden bg-gray-100">
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
                                    <button id="prevArrow" class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/90 hover:bg-white rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                                        <svg class="w-7 h-7 text-gray-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button id="nextArrow" class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/90 hover:bg-white rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                                        <svg class="w-7 h-7 text-gray-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <button id="zoomBtn" class="absolute bottom-8 right-8 w-14 h-14 bg-white/90 hover:bg-white rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center group z-10">
                                        <svg class="w-7 h-7 text-gray-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Thumbnail Navigation -->
                                @if(count($listing->images) > 1)
                                <div class="bg-gray-50 p-6">
                                    <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                                        @foreach($listing->images as $index => $image)
                                        <button class="thumbnail-btn flex-shrink-0 w-24 h-20 md:w-28 md:h-24 rounded-lg overflow-hidden border-3 transition-all duration-200 {{ $index === 0 ? 'border-blue-500 shadow-lg scale-105' : 'border-gray-200 hover:border-gray-300' }}"
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
                                        <button class="dot-indicator w-3 h-3 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-blue-500 w-8' : 'bg-gray-300' }}"
                                                data-slide="{{ $i }}"></button>
                                        @endfor
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <!-- No Images Placeholder -->
                            <div class="h-[400px] md:h-[500px] lg:h-[600px] bg-gray-100 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-32 h-32 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="text-2xl font-medium text-gray-500 mb-3">No Images Available</h3>
                                    <p class="text-gray-400">Property images will be displayed here</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Property Details Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Main Content -->
                            <div class="lg:col-span-2 space-y-8">
                                <!-- Property Features -->
                                <div class="bg-white rounded-xl shadow-sm p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Property Details</h2>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 font-medium">Bedrooms</p>
                                            <p class="font-bold text-xl text-gray-900">{{ $listing->room_count ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-center p-4 bg-green-50 rounded-lg">
                                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 font-medium">Bathrooms</p>
                                            <p class="font-bold text-xl text-gray-900">{{ $listing->bathroom_count ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 font-medium">Property Type</p>
                                            <p class="font-bold text-xl text-gray-900">{{ $listing->property_type ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 font-medium">Area</p>
                                            <p class="font-bold text-xl text-gray-900">{{ $listing->area ?? 'N/A' }} sqm</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Property Description -->
                                <div class="bg-white rounded-xl shadow-sm p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Description</h2>
                                    <div class="prose prose-lg max-w-none">
                                        <p class="text-gray-700 leading-relaxed text-lg">
                                            {{ $listing->description ?? 'No description available for this property.' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Amenities -->
                                @if($listing->amenities)
                                <div class="bg-white rounded-xl shadow-sm p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Amenities</h2>
                                    <div class="flex flex-wrap gap-3">
                                        @php
                                            $amenities = is_string($listing->amenities) ? explode(',', $listing->amenities) : (is_array($listing->amenities) ? $listing->amenities : []);
                                        @endphp
                                        @foreach($amenities as $amenity)
                                            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                                {{ trim($amenity) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Sidebar -->
                            <div class="space-y-8">
                                <!-- Property Status -->
                                <div class="bg-white rounded-xl shadow-sm p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Property Status</h2>
                                    <div class="flex items-center mb-4">
                                        @if($listing->status === 'available')
                                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                            <span class="text-green-600 font-bold text-lg">Available</span>
                                        @elseif($listing->status === 'rented')
                                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                            <span class="text-red-600 font-bold text-lg">Rented</span>
                                        @elseif($listing->status === 'banned')
                                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                            <span class="text-red-600 font-bold text-lg">Banned</span>
                                        @else
                                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                                            <span class="text-yellow-600 font-bold text-lg">{{ ucfirst($listing->status ?? 'Unknown') }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Listed {{ $listing->created_at ? $listing->created_at->diffForHumans() : 'recently' }}
                                    </p>
                                </div>

                                <!-- Quick Stats -->
                                <div class="bg-white rounded-xl shadow-sm p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Stats</h2>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Views</span>
                                            <span class="font-bold text-lg">{{ $listing->views ?? 0 }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Favorites</span>
                                            <span class="font-bold text-lg">{{ $listing->favorites_count ?? 0 }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Likes</span>
                                            <span class="font-bold text-lg">{{ $listing->likes_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Carousel Manager Script -->
<script src="{{ asset('js/carouselManager.js') }}"></script>

@endsection
