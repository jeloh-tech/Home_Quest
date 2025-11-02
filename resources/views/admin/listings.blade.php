@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-white">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')
    
    <div class="flex-1 min-h-screen bg-white ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
<div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">All Listings</h1>
                    <p class="text-sm text-gray-600 mt-1">Admin Features: Manage all property listings with full control</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.listings') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition-colors">All</a>
                    <a href="{{ route('admin.listings.active') }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition-colors">Active</a>
                    <a href="{{ route('admin.listings.rented') }}" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition-colors">Rented</a>
                    <a href="{{ route('admin.listings.banned') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700 transition-colors">Banned</a>
                </div>
                </div>

                <!-- Search form -->
                <form method="GET" action="{{ route('admin.listings') }}" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300"
                                placeholder="Search listings by title, description, location, or landlord name...">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.listings') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-500/30 transition-all duration-300">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
                
                <div>
                    @if(count($listings) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($listings as $listing)
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                                    <div class="relative h-48 bg-gray-200">
                                        @if($listing->images && is_array($listing->images) && count($listing->images) > 0)
                                            <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                                        @elseif($listing->featured_image)
                                            <img src="{{ asset('storage/' . $listing->featured_image) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-full object-cover">
                                        @endif
                                        <!-- Landlord Profile Image -->
                                        @if($listing->user && $listing->user->profile_photo_path)
                                            <div class="absolute top-3 left-3">
                                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg">
                                                    <img src="{{ asset('storage/' . $listing->user->profile_photo_path) }}" alt="{{ $listing->user->name }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="bg-white bg-opacity-90 rounded px-2 py-1 mt-1 text-xs font-medium text-gray-700 shadow">
                                                    {{ $listing->created_at->format('M j, Y') }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="absolute top-3 left-3">
                                                <div class="w-10 h-10 rounded-full bg-gray-500 border-2 border-white shadow-lg flex items-center justify-center">
                                                    <span class="text-white text-sm font-semibold">{{ substr($listing->user->name ?? 'U', 0, 1) }}</span>
                                                </div>
                                                <div class="bg-white bg-opacity-90 rounded px-2 py-1 mt-1 text-xs font-medium text-gray-700 shadow">
                                                    {{ $listing->created_at->format('M j, Y') }}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3 bg-white bg-opacity-75 rounded-full px-3 py-1 text-sm font-semibold text-gray-900 shadow">
                                            ₱{{ number_format($listing->price, 2) }}/month
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $listing->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($listing->description, 60) }}</p>
                                        <p class="text-sm text-gray-700 flex items-center mb-1">
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $listing->location }}
                                        </p>
                                        <p class="text-sm text-gray-700 flex space-x-4 mb-2">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $listing->room_count }} rooms
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                </svg>
                                                {{ $listing->bathroom_count }} baths
                                            </span>
                                        </p>
                                        <p class="mb-2">
                                            @switch($listing->status)
                                                @case('active')
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                    @break
                                                @case('rented')
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rented</span>
                                                    @break
                                                @case('banned')
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-800 text-white">Banned</span>
                                                    @break
                                                @default
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                            @endswitch
                                        </p>
                                        <p class="text-sm text-gray-900 font-medium mb-1">Email: {{ $listing->user->email ?? 'N/A' }}</p>
                                        @if($listing->status === 'rented')
                                            <p class="text-sm text-gray-900 font-medium mb-3">Tenant: {{ $listing->tenant->name ?? 'N/A' }}</p>
                                        @else
                                            <p class="mb-3"></p>
                                        @endif
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.listings.show', $listing) }}" class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">View Details</a>
                                            @if($listing->status === 'banned')
                                                <form method="POST" action="{{ route('admin.listings.unban', $listing) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">Unban</button>
                                                </form>
                                            @elseif($listing->status !== 'rented')
                                                <form method="POST" action="{{ route('admin.listings.delete', $listing) }}" onsubmit="return confirm('Are you sure you want to delete this listing?')" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">Remove</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-10">No listings found.</div>
                    @endif
                </div>
                
                <div class="mt-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
