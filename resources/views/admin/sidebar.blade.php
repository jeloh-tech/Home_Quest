<div class="fixed left-0 top-0 h-screen bg-white text-black w-64 shadow-lg border-r border-gray-200 flex flex-col">
    <!-- Logo/Header Section -->
    <div class="flex-shrink-0 flex items-center justify-between h-20 border-b border-gray-200 bg-gray-50 px-6">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('storage/favicon/loga.png') }}" alt="Home Quest Logo" class="h-10 w-10">
            <span class="text-lg font-bold text-gray-900">Home Quest</span>
        </div>

        <!-- Notification Bell Icon -->
        <div class="relative">
            <button class="p-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 relative" title="Notifications">
                <svg class="w-6 h-6 text-gray-600 hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                    0
                </span>
            </button>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="flex-shrink-0 px-4 py-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg">
                    {{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'A' }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-black">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-600">Administrator</p>
            </div>
        </div>
    </div>

    <!-- Scrollable Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Users Management -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Users</p>

            @include('admin.sidebar-all-users')
            @include('admin.sidebar-tenants')
            @include('admin.sidebar-landlords')
        </div>

        <!-- Property Management -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Properties</p>
            
            <a href="{{ route('admin.listings') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.listings') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v3H7V8z"></path>
                </svg>
                <span>All Listings</span>
            </a>

            <a href="{{ route('admin.listings.active') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.listings.active') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Active Listings</span>
            </a>

            <a href="{{ route('admin.listings.rented') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.listings.rented') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Rented Properties</span>
            </a>
        </div>

        <!-- Verification -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Verification</p>
            
            <a href="{{ route('admin.verification.landlords') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.verification.landlords') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Application</span>
                @if(isset($pendingVerificationsCount) && $pendingVerificationsCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 font-bold">
                        {{ $pendingVerificationsCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Reports -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</p>

            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.index', 'admin.reports.show') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Reports Management</span>
            </a>

            <a href="{{ route('admin.analytics') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Analytics</span>
            </a>

            <a href="{{ route('admin.payment-history') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payment-history') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Payment History</span>
            </a>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="flex-shrink-0 px-4 py-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-black hover:bg-gray-100 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
