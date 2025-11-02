@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Property Search</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Start your search for the perfect place to stay...</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Filter Properties</h2>
                <form method="GET" action="{{ route('tenant.search') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Price</label>
                            <input type="number" name="min_price" id="min_price" value="{{ old('min_price', $filters['min_price'] ?? '') }}" placeholder="₱0" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Price</label>
                            <input type="number" name="max_price" id="max_price" value="{{ old('max_price', $filters['max_price'] ?? '') }}" placeholder="₱50,000" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="room_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Count</label>
                            <input type="number" name="room_count" id="room_count" value="{{ old('room_count', $filters['room_count'] ?? '') }}" placeholder="Any" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="bathroom_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bathroom Count</label>
                            <input type="number" name="bathroom_count" id="bathroom_count" value="{{ old('bathroom_count', $filters['bathroom_count'] ?? '') }}" placeholder="Any" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $filters['location'] ?? '') }}" placeholder="Any" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Property Listings -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Property Listings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $listing['title'] }}</h3>
                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">₱{{ number_format($listing['price'], 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $listing['location'] }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $listing['room_count'] }} rooms</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $listing['bathroom_count'] }} bathrooms</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $listing['bathroom_count'] }} bathrooms</span>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('tenant.property-details', $listing['id']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
