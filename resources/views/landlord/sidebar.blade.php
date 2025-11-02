<div class="fixed left-0 top-0 h-screen bg-white text-black w-64 shadow-lg border-r border-gray-200 flex flex-col">
    <!-- Logo/Header Section -->
    <div class="flex-shrink-0 flex items-center justify-between h-20 border-b border-gray-200 bg-gray-50 px-6">
<div class="flex items-center space-x-3">
    <img src="{{ asset('storage/favicon/loga.png') }}" alt="Home Quest Logo" class="h-8 w-8">
    <span class="font-medium">Home Quest</span>
</div>

        <!-- Notification Bell Icon -->
        <div class="relative">
            <button class="p-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 relative" title="Notifications">
                <svg class="w-6 h-6 text-gray-600 hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                    3
                </span>
            </button>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="flex-shrink-0 px-4 py-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-md overflow-hidden border-2 border-gray-200">
                @if(Auth::user() && Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Picture" class="sidebar-profile-img w-full h-full object-cover rounded-full">
                @else
                    <span class="sidebar-profile-initial text-white font-bold text-lg">
                        {{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'P' }}
                    </span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-black">{{ Auth::user() ? Auth::user()->name : 'Property Owner' }}</p>
                <p class="text-xs text-gray-600">Landlord</p>
            </div>
        </div>
    </div>

    <!-- Scrollable Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('landlord.dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.dashboard') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Profile Management -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Profile</p>
            <a href="{{ route('landlord.profile') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.profile') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">My Profile</span>
            </a>
            <a href="{{ route('landlord.verify') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.verify') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Verification</span>
            </a>
        </div>

        <!-- Property Management -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Properties</p>
            <a href="{{ route('landlord.add-post') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.add-post') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium">Add Property</span>
            </a>
            <a href="{{ route('landlord.properties') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.properties') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-medium">My Post</span>
                @if(isset($pendingApplicationsCount) && $pendingApplicationsCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 font-bold">
                        {{ $pendingApplicationsCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Rent Payments -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rent Payments</p>
            <a href="{{ route('landlord.payment-history') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.payment-history') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">Payment History</span>
            </a>
        </div>

        
        <!-- Maintenance -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Maintenance</p>
            <a href="{{ route('landlord.maintenance-requests') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.maintenance-requests') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Maintenance Requests</span>
            </a>
        </div>

        <!-- Communication -->
        <div class="space-y-1">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Communication</p>
            <a href="{{ route('landlord.messages') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('landlord.messages') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span class="font-medium">Messages</span>
                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 font-bold">
                        {{ $unreadMessagesCount }}
                    </span>
                @endif
            </a>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="flex-shrink-0 px-4 py-4 border-t border-gray-200">
        <form method="POST" action="{{ route('landlord.logout') }}">
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
