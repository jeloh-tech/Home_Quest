@extends('layouts.app')

@section('content')
<script>
    window.favoriteListingIds = @json($favoriteListingIds ?? []);
    window.likedListingIds = @json($likedListingIds ?? []);
    window.likeCounts = @json($likeCounts ?? []);
    window.isAuthenticated = @json(Auth::check());
</script>
<!-- eslint-disable -->
@if(isset($isPublic) && $isPublic)
    <!-- Public View - No Sidebar -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="favoritesManager()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Find Your Perfect Rental</h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">Discover student rooms and boarding houses near you</p>
            </div>
@else
    <!-- Authenticated Tenant View -->
    <div class="flex h-screen overflow-hidden" x-data="favoritesManager()" x-init="init()">
        <!-- Fixed Sidebar on Left -->
        <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
            @include('tenant.sidebar')
        </aside>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
                <!-- Header Section -->
                <div class="mb-8 relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-lg text-gray-600 dark:text-gray-300">Easily find student rooms and boarding houses near you.</p>
                        </div>

                        @include('components.login-success-modal')
                    </div>
                </div>
@endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Views -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Views</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ count($listings) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Applications -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Applications</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Messages</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Saved Searches -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Saved Searches</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Left Column - Filters -->
                <div class="lg:col-span-1">
                    <!-- Filter Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm sticky top-6">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Refine Search</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Find your perfect rental</p>
                        </div>
                        <div class="p-6">
                            <form method="GET" action="{{ route('tenant.dashboard') }}" class="space-y-4">
                                <!-- Current Sort (hidden) -->
                                <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                                <!-- Location Filter -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                    <input type="text" name="location" id="location" value="{{ old('location', $filters['location'] ?? '') }}" placeholder="Enter city or area" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>

                                <!-- Price Range -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Price</label>
                                        <input type="number" name="min_price" id="min_price" value="{{ old('min_price', $filters['min_price'] ?? '') }}" placeholder="₱0" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                    <div>
                                        <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Price</label>
                                        <input type="number" name="max_price" id="max_price" value="{{ old('max_price', $filters['max_price'] ?? '') }}" placeholder="₱50,000" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <!-- Room and Bathroom Count -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="room_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Rooms</label>
                                        <input type="number" name="room_count" id="room_count" value="{{ old('room_count', $filters['room_count'] ?? '') }}" placeholder="Any" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                    <div>
                                        <label for="bathroom_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Bathrooms</label>
                                        <input type="number" name="bathroom_count" id="bathroom_count" value="{{ old('bathroom_count', $filters['bathroom_count'] ?? '') }}" placeholder="Any" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-3 pt-4">
                                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        Search
                                    </button>
                                    <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Properties Grid -->
                <div class="lg:col-span-3">
                    <!-- Results Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Available Properties</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ count($listings) }} {{ Str::plural('property', count($listings)) }} found
                                @if($filters)
                                    with current filters
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Sort Dropdown -->
                            <form method="GET" action="{{ route('tenant.dashboard') }}" class="inline">
                                @foreach($filters as $key => $value)
                                    @if($value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="newest" {{ (request('sort', 'newest') == 'newest') ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ (request('sort', 'newest') == 'price_low') ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ (request('sort', 'newest') == 'price_high') ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="popular" {{ (request('sort', 'newest') == 'popular') ? 'selected' : '' }}>Most Popular</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Properties Grid -->
                    @if(count($listings) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                            @foreach($listings as $listing)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
                                    <!-- Property Image -->
                                    <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                                        @if($listing->images && is_array($listing->images) && count($listing->images) > 0)
                                            <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                                        @elseif($listing->featured_image)
                                            <img src="{{ asset('storage/' . $listing->featured_image) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-full object-cover">
                                        @endif

                                        <!-- Landlord Profile and Date -->
                                        @if($listing->user)
                                            <div class="absolute top-3 left-3 flex items-start space-x-2">
                                                @if($listing->user->profile_photo_path)
                                                    <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white shadow-lg">
                                                        <img src="{{ asset('storage/' . $listing->user->profile_photo_path) }}" alt="{{ $listing->user->name }}" class="w-full h-full object-cover">
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-gray-500 border-2 border-white shadow-lg flex items-center justify-center">
                                                        <span class="text-white text-xs font-semibold">{{ substr($listing->user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="bg-white bg-opacity-90 rounded px-2 py-1 text-xs font-medium text-gray-700 shadow">
                                                    {{ $listing->created_at->format('M j, Y') }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="absolute top-3 left-3">
                                                <button class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif

                                        <div class="absolute top-3 right-3">
                                            <span class="px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium rounded-full shadow-sm">
                                                ₱{{ number_format($listing->price, 0) }}/month
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Property Details -->
                                    <div class="p-6">
                                        <div class="mb-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $listing->title }}</h3>
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex flex-col items-center">
                                                        <button
                                                            class="p-2.5 rounded-full shadow-sm transition-colors"
                                                            :class="favoriteListingIds.includes({{ $listing->id }}) ? 'bg-red-100 dark:bg-red-700' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                                            @click.prevent="toggleFavorite({{ $listing->id }})"
                                                            aria-label="Toggle Favorite"
                                                        >
                                                            <svg
                                                                class="w-6 h-6"
                                                                :class="favoriteListingIds.includes({{ $listing->id }}) ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400'"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            >
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <button
                                                        class="flex items-center space-x-1 p-2.5 rounded-full shadow-sm transition-colors"
                                                        :class="likedListingIds.includes({{ $listing->id }}) ? 'bg-blue-100 dark:bg-blue-700' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                                        @click.prevent="toggleLike({{ $listing->id }})"
                                                        aria-label="Toggle Like"
                                                    >
                                                        <svg
                                                            class="w-6 h-6"
                                                            :class="likedListingIds.includes({{ $listing->id }}) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                        </svg>
                                                        <span class="text-xs text-gray-600 dark:text-gray-400" x-text="likeCounts[{{ $listing->id }}] ?? 0"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $listing->location ?? 'Location not specified' }}
                                            </p>
                                        </div>

                                        <!-- Property Features -->
                                        <div class="flex items-center space-x-4 mb-4">
                                            @if($listing->room_count)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    {{ $listing->room_count }} rooms
                                                </div>
                                            @endif
                                            @if($listing->bathroom_count)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                    </svg>
                                                    {{ $listing->bathroom_count }} baths
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Property Description -->
                                        @if($listing->description)
                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($listing->description, 100) }}</p>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-3">
                                            <a href="{{ route('tenant.property-details', $listing->id) }}" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors text-sm font-medium text-center">
                                                View Details
                                            </a>
                                            <a href="{{ route('tenant.messages.conversation', $listing->user->id) }}" class="flex items-center justify-center space-x-2 px-4 py-2 border-2 border-indigo-200 dark:border-indigo-700 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300 dark:hover:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 text-sm font-medium group">
                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span>Contact</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $listings->links() }}
                        </div>
                    @else
                        <!-- No Properties Found -->
                        <div class="text-center py-16">
                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No properties found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                @if($filters)
                                    No properties match your current filters. Try adjusting your search criteria.
                                @else
                                    There are no properties available at the moment. Check back later!
                                @endif
                            </p>
                            @if($filters)
                                <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
