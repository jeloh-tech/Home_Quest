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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Advanced Search</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Find your perfect rental with advanced filters</p>
            </div>

            <!-- Advanced Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Advanced Filters</h2>
                <form method="GET" action="{{ route('tenant.advanced-search') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Price Range -->
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Price</label>
                            <input type="number" name="min_price" id="min_price" value="{{ old('min_price', $filters['min_price'] ?? '') }}" placeholder="₱0" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Price</label>
                            <input type="number" name="max_price" id="max_price" value="{{ old('max_price', $filters['max_price'] ?? '') }}" placeholder="₱50,000" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <!-- Room Count -->
                        <div>
                            <label for="min_rooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Rooms</label>
                            <input type="number" name="min_rooms" id="min_rooms" value="{{ old('min_rooms', $filters['min_rooms'] ?? '') }}" placeholder="1" min="1" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label for="max_rooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Rooms</label>
                            <input type="number" name="max_rooms" id="max_rooms" value="{{ old('max_rooms', $filters['max_rooms'] ?? '') }}" placeholder="10" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <!-- Bathroom Count -->
                        <div>
                            <label for="min_bathrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Bathrooms</label>
                            <input type="number" name="min_bathrooms" id="min_bathrooms" value="{{ old('min_bathrooms', $filters['min_bathrooms'] ?? '') }}" placeholder="1" min="1" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label for="property_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Type</label>
                            <select name="property_type" id="property_type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Types</option>
                                <option value="apartment" {{ old('property_type', $filters['property_type'] ?? '') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="house" {{ old('property_type', $filters['property_type'] ?? '') == 'house' ? 'selected' : '' }}>House</option>
                                <option value="condo" {{ old('property_type', $filters['property_type'] ?? '') == 'condo' ? 'selected' : '' }}>Condo</option>
                                <option value="townhouse" {{ old('property_type', $filters['property_type'] ?? '') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                            </select>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $filters['location'] ?? '') }}" placeholder="City, neighborhood, etc." class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <!-- Amenities -->
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amenities</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="parking" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Parking</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="pool" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pool</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="gym" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Gym</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="laundry" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Laundry</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="pet-friendly" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pet Friendly</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="furnished" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Furnished</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('tenant.advanced-search') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Clear Filters
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Search Results</h2>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $listings->total() }} properties found</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $listing->title ?? 'Property Title' }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $listing->location ?? 'Location' }}</p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($listing->price ?? 1500, 2) }}/month</span>
                                    <div class="flex space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ $listing->room_count ?? 2 }} rooms</span>
                                        <span>•</span>
                                        <span>{{ $listing->bathroom_count ?? 1 }} baths</span>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        View Details
                                    </button>
                                    <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
