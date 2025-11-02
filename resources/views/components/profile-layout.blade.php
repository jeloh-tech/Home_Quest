<div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-100 dark:bg-gray-800 p-6 space-y-6 flex-shrink-0 rounded-l-lg">
        <nav class="space-y-4">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded bg-gray-300 dark:bg-gray-700 font-semibold">Profile</a>
            {{-- Removed Dashboard link to prevent navigation to other page --}}
            {{-- <a href="{{ route('profile.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 font-semibold">Dashboard</a> --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-red-600 hover:text-white dark:hover:bg-red-700 font-semibold text-red-600 dark:text-red-400">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-grow p-6">
        {{ $slot }}
    </main>
</div>
