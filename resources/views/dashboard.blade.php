@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-gray-100 p-6 space-y-6 flex-shrink-0 rounded-l-lg">
        <div class="flex items-center justify-center h-16 border-b border-gray-700">
            <span class="text-lg font-semibold">Tenant Menu</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-gray-700' : '' }}">Profile</a>

            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Favorites</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Map</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Messages</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">My Rental</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-red-600 hover:text-white dark:hover:bg-red-700 font-semibold text-red-600 dark:text-red-400">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-grow p-6 bg-gray-50 dark:bg-gray-900">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Listings</h1>
        <form class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Price</label>
                    <input type="number" name="min_price" id="min_price" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                </div>
                <div>
                    <label for="room_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Count</label>
                    <input type="number" name="room_count" id="room_count" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Price</label>
                    <input type="number" name="max_price" id="max_price" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                </div>
                <div>
                    <label for="bathroom_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bathroom Count</label>
                    <input type="number" name="bathroom_count" id="bathroom_count" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                    <input type="text" name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                </div>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Filter</button>
        </form>
    </main>
</div>
