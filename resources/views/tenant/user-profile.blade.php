@extends('layouts.app')

@section('content')
<script>
    window.favoriteListingIds = @json($favoriteListingIds ?? []);
    window.likedListingIds = @json($likedListingIds ?? []);
    window.likeCounts = @json($likeCounts ?? []);
    window.isAuthenticated = @json(Auth::check());
</script>
<!-- eslint-disable -->
@if(Auth::check() && Auth::user()->role === 'tenant')
<div class="flex h-screen overflow-hidden" x-data="favoritesManager()" x-init="init()">
<!-- eslint-enable -->
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen bg-fixed">
@else
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 bg-fixed pt-16" x-data="favoritesManager()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4">
@endif


            <!-- User Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8">
                <div class="flex items-center space-x-6">
                    <!-- Profile Picture -->
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-500">
                                <span class="text-white text-2xl font-semibold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- User Details -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                                <p class="text-gray-900 dark:text-white">{{ $user->email }}</p>
                            </div>
                            @if($user->phone)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                                    <p class="text-gray-900 dark:text-white">{{ $user->phone }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Role</p>
                                <p class="text-gray-900 dark:text-white capitalize">{{ $user->role }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Member Since</p>
                                <p class="text-gray-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Properties by {{ $user->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $listings->total() }} {{ Str::plural('property', $listings->total()) }} found
                        </p>
                    </div>
                </div>

                @if($listings->count() > 0)
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

                                    <!-- Date -->
                                    <div class="absolute top-3 left-3">
                                        <div class="bg-white bg-opacity-90 rounded px-2 py-1 text-xs font-medium text-gray-700 shadow">
                                            {{ $listing->created_at->format('M j, Y') }}
                                        </div>
                                    </div>

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
                                                @if(Auth::check() && Auth::user()->role === 'tenant')
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
                                                @endif
                                                @if(Auth::check() && Auth::user()->role === 'tenant')
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
                                                @else
                                                <x-like-button :listing="$listing" />
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $listing->location }}
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
                                        <a href="{{ Auth::check() && Auth::user()->role === 'tenant' ? route('tenant.property-details', $listing->id) : route('property-details', $listing->id) }}" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors text-sm font-medium text-center">
                                            View Details
                                        </a>
                                        @if(Auth::check() && Auth::user()->role === 'tenant')
                                        <a href="{{ route('tenant.messages.conversation', $user->id) }}" class="flex items-center justify-center space-x-2 px-4 py-2 border-2 border-indigo-200 dark:border-indigo-700 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300 dark:hover:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 text-sm font-medium group">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span>Contact</span>
                                        </a>
                                        @endif
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
                            This landlord hasn't posted any properties yet.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
