@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-800 text-gray-100 w-64">
    <div class="flex items-center justify-center h-16 border-b border-gray-700">
        <span class="text-lg font-semibold">Tenant Menu</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-gray-700' : '' }}">Profile</a>
        <a href="{{ url('/listings') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('listings') ? 'bg-gray-700' : '' }}">Listings</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Favorites</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Map</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Messages</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">My Rental</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-red-600 hover:text-white dark:hover:bg-red-700 font-semibold text-red-600 dark:text-red-400">Logout</button>
        </form>
    </nav>
</div>
<main class="flex-grow p-6 bg-gray-50 dark:bg-gray-900">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Profile</h1>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex items-start space-x-6">
        @csrf
        @method('PUT')
        <!-- Profile Image -->
        <div class="w-24 flex flex-col items-center">
            <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 text-xl font-semibold overflow-hidden">
                @if(auth()->user()->profile_photo_path)
                    <img id="profileImagePreview" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                @else
                    <span id="profileImagePreview" class="w-full h-full flex items-center justify-center">Avatar</span>
                @endif
            </div>
            <label for="profile_photo" class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer hover:underline">Change Photo</label>
            <input id="profile_photo" type="file" name="profile_photo" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100
                cursor-pointer" />
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
        </div>

        <script>
            document.getElementById('profile_photo').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = document.getElementById('profileImagePreview');
                    if(preview.tagName === 'IMG') {
                        preview.src = URL.createObjectURL(file);
                    } else {
                        // Replace the span with an img element
                        const img = document.createElement('img');
                        img.id = 'profileImagePreview';
                        img.className = 'w-full h-full object-cover';
                        img.src = URL.createObjectURL(file);
                        preview.replaceWith(img);
                    }
                }
            });
        </script>

        <!-- Profile Info -->
        <div class="flex-grow border border-gray-300 dark:border-gray-700 rounded p-6">
            <p class="mb-2"><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p class="mb-2"><strong>Phone:</strong> {{ auth()->user()->phone ?? 'N/A' }}</p>
            <p class="mb-2"><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
        </div>
    </form>
</main>
@endsection
