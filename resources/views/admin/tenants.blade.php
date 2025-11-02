@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-white">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')
    
    <div class="flex-1 min-h-screen bg-gradient-to-br from-blue-50 to-white ml-64">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-10">
            

            <!-- Search input -->
            <form id="searchForm" method="GET" action="{{ route('admin.tenants') }}">
                    <div class="relative z-10 flex items-center space-x-4">
                        <!-- Search Icon -->
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" name="tenant_search" id="tenant_search" value="{{ request('tenant_search') }}" placeholder="Search tenants by name or email..."
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-300 text-gray-900 font-medium" />
                    </div>
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($tenants as $tenant)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-gray-200 p-6 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-50/30 to-emerald-50/30 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <!-- Profile Section -->
                                <div class="flex flex-col items-center text-center mb-4">
                                    <div class="relative flex-shrink-0 h-16 w-16 rounded-full flex items-center justify-center overflow-hidden mb-3 {{ $tenant->profile_photo_path ? '' : 'bg-gradient-to-br from-blue-400 to-blue-500 shadow-lg' }}">
                                        @if($tenant->profile_photo_path)
                                            <img src="{{ asset('storage/' . $tenant->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span class="text-xl font-bold text-white">{{ substr($tenant->name, 0, 1) }}</span>
                                        @endif
                                        @if($tenant->verification_status === 'banned')
                                            <div class="absolute inset-0 bg-red-500 bg-opacity-80 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-white">SUSPENDED</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-lg font-bold text-gray-900 mb-1">{{ $tenant->name }}</div>
                                    <div class="text-xs text-gray-500 mb-2">Joined {{ $tenant->created_at->format('M j, Y') }}</div>
                                </div>

                                <!-- Details Section -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-700 truncate">{{ $tenant->email }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $tenant->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex justify-center mb-4">
                                    @if($tenant->verification_status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-200 to-green-300 text-green-900 border border-green-400 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" />
                                            </svg>
                                            Active
                                        </span>
                                    @elseif($tenant->verification_status === 'banned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-200 to-red-300 text-red-900 border border-red-400 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            Suspended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-200 to-yellow-300 text-yellow-900 border border-yellow-400 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions Section -->
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition duration-300 text-center text-sm shadow-md hover:shadow-lg">
                                        View Details
                                    </a>
                                    <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-red-600 hover:to-red-700 transition duration-300 text-sm shadow-md hover:shadow-lg"
                                                onclick="return confirm('Are you sure you want to delete this tenant?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when search input changes
    const searchInput = document.getElementById('tenant_search');
    const searchForm = document.getElementById('searchForm');

    // Debounce search input
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500);
    });
});
</script>
@endsection
