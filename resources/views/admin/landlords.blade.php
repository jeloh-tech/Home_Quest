@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-white">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <div class="flex-1 min-h-screen bg-gradient-to-br from-blue-50 to-white ml-64">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-10">


            <!-- Filters Section -->
            
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 text-gray-900 font-medium" form="searchForm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" name="status_filter" class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" form="searchForm">
                        <option value="" {{ request('status_filter') == '' ? 'selected' : '' }}>All Status</option>
                        <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="declined" {{ request('status_filter') == 'declined' ? 'selected' : '' }}>Declined</option>
                    </select>
                    </div>

                    <!-- Properties Filter -->
                    <div>
                        <label for="propertiesFilter" class="block text-sm font-medium text-gray-700 mb-2">Properties</label>
                    <select id="propertiesFilter" name="properties_filter" class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" form="searchForm">
                        <option value="" {{ request('properties_filter') == '' ? 'selected' : '' }}>All</option>
                        <option value="0" {{ request('properties_filter') == '0' ? 'selected' : '' }}>No Properties</option>
                        <option value="1-5" {{ request('properties_filter') == '1-5' ? 'selected' : '' }}>1-5 Properties</option>
                        <option value="6+" {{ request('properties_filter') == '6+' ? 'selected' : '' }}>6+ Properties</option>
                    </select>
                    </div>
                </div>
            </div>

            <!-- Landlords Grid -->
            <form id="searchForm" method="GET" action="{{ route('admin.landlords') }}">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($landlords as $landlord)
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-md border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-indigo-50/30 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <!-- Profile Section -->
                                    <div class="flex flex-col items-center text-center mb-4">
                                        <div class="relative flex-shrink-0 h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg overflow-hidden mb-3">
                                            @if($landlord->profile_photo_path)
                                                <img src="{{ asset('storage/' . $landlord->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                                            @else
                                                <span class="text-xl font-bold text-white">{{ substr($landlord->name, 0, 1) }}</span>
                                            @endif
                                            @if($landlord->verification_status === 'banned')
                                                <div class="absolute inset-0 bg-red-500 bg-opacity-80 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-bold text-white">SUSPENDED</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-lg font-bold text-gray-900 mb-1">{{ $landlord->name }}</div>
                                        <div class="text-xs text-gray-500 mb-2">Joined {{ $landlord->created_at->format('M j, Y') }}</div>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-700 truncate">{{ $landlord->email }}</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $landlord->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $landlord->listings_count ?? 0 }} properties</span>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex justify-center mb-4">
                                        @if($landlord->verification_status === 'approved')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-gray-400 shadow-sm">
                                                {{ ucfirst($landlord->verification_status) }}
                                            </span>
                                        @elseif($landlord->verification_status === 'banned')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-200 to-red-300 text-red-900 border border-red-400 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-gray-400 shadow-sm">
                                                {{ ucfirst($landlord->verification_status ?? 'pending') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Actions Section -->
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('admin.landlords.show', $landlord) }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition duration-300 text-center text-sm shadow-md hover:shadow-lg">
                                            View Details
                                        </a>
                                        <form action="{{ route('admin.landlords.destroy', $landlord) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-red-600 hover:to-red-700 transition duration-300 text-sm shadow-md hover:shadow-lg"
                                                    onclick="return confirm('Are you sure you want to delete {{ $landlord->name }}?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm">No landlords found</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                <!-- Pagination -->
                @if($landlords->hasPages())
                    <div class="mt-6">
                        {{ $landlords->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');
    const propertiesFilter = document.getElementById('propertiesFilter');
    const searchForm = document.getElementById('searchForm');

    // Debounce search input
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500);
    });

    // Submit on filter change
    statusFilter.addEventListener('change', function() {
        searchForm.submit();
    });

    propertiesFilter.addEventListener('change', function() {
        searchForm.submit();
    });
});
</script>
@endsection
