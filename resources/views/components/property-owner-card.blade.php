@if($listing->user)
<div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 dark:border-gray-700/20">
    <div class="flex flex-col space-y-6">
        <!-- Professional Layout: Profile | Button | Price -->
        <div class="flex items-center justify-between">
            <!-- Left Side: Owner Info -->
            <div class="flex items-center space-x-4">
                <!-- Profile Picture -->
                <div class="relative">
                    <div class="w-16 h-16 rounded-full overflow-hidden ring-4 ring-white/50 dark:ring-gray-600/50 shadow-lg">
                        @if($listing->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $listing->user->profile_photo_path) }}" alt="Profile Picture" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <!-- Verified Badge -->
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Owner Info -->
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $listing->user->name }}</p>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Property Owner</span>
                        <div class="ml-2 flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">Verified</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Visit Account Button -->
            <div class="flex justify-center">
                <a href="{{ route('user.show.public', $listing->user->id) }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 hover:from-blue-700 hover:via-blue-800 hover:to-blue-900 text-white font-bold py-4 px-10 rounded-2xl shadow-2xl transform hover:scale-105 hover:shadow-3xl transition-all duration-300 text-lg border-2 border-white/20 backdrop-blur-sm">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="tracking-wide">Visit Account</span>
                </a>
            </div>

            <!-- Right Side: Price -->
            <div class="text-right">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 leading-tight">₱{{ number_format($listing->price, 2) }}</p>
                <p class="text-sm opacity-90 font-medium text-gray-600 dark:text-gray-400">per month</p>
            </div>
        </div>
    </div>
</div>
@endif
