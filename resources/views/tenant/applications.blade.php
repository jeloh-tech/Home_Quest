@extends('layouts.app')

@section('content')
<script>
    window.favoriteListingIds = @json($favoriteListingIds ?? []);
    window.likedListingIds = @json($likedListingIds ?? []);
    window.likeCounts = @json($likeCounts ?? []);
    window.isAuthenticated = @json(Auth::check());
</script>
<!-- eslint-disable -->
<div class="flex h-screen overflow-hidden" x-data="favoritesManager()" x-init="init()">
<!-- eslint-enable -->
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Rental Applications</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Track the status of your rental applications</p>
            </div>

            <!-- Applications Grid -->
            @if($applications->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    @foreach($applications as $application)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
                            <!-- Application Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $application->listing->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $application->listing->location ?? 'Location not specified' }}
                                        </p>
                                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                            ₱{{ number_format($application->listing->price, 0) }}/month
                                        </p>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex-shrink-0">
                                        @if($application->status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending Review
                                            </span>
                                        @elseif($application->status === 'accepted')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Accepted
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Rejected
                                            </span>
                                        @elseif($application->status === 'cancelled')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Cancelled
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Landlord Info -->
                                <div class="flex items-center space-x-3">
                                    @if($application->listing->user && $application->listing->user->profile_photo_path)
                                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-600">
                                            <img src="{{ asset('storage/' . $application->listing->user->profile_photo_path) }}" alt="{{ $application->listing->user->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-gray-600 dark:text-gray-300 text-sm font-semibold">
                                                {{ substr($application->listing->user->name ?? 'L', 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            Landlord: {{ $application->listing->user->name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Applied {{ $application->created_at->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Application Details -->
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Move-in Date</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($application->planned_move_in_date)->format('M j, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">End Date</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($application->planned_end_date)->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Occupants</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $application->occupants }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <a href="{{ route('tenant.property-details', $application->listing->id) }}" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors text-sm font-medium text-center">
                                        View Property
                                    </a>

                                    @if($application->status === 'pending')
                                        <form method="POST" action="{{ route('tenant.rental-application.cancel', $application) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors text-sm font-medium">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($application->status === 'cancelled' && $application->cancelled_at && $application->cancelled_at->addHour()->isFuture())
                                        <form method="POST" action="{{ route('tenant.rental-application.undo-cancel', $application) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 border border-green-300 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-50 dark:hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors text-sm font-medium">
                                                Undo Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if($application->status === 'cancelled' && $application->cancelled_at && $application->cancelled_at->addHour()->isFuture())
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        You can undo this cancellation within {{ $application->cancelled_at->addHour()->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $applications->links() }}
                </div>
            @else
                <!-- No Applications Found -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No rental applications yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        You haven't applied for any properties yet. Start browsing available rentals to submit your first application.
                    </p>
                    <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        Browse Properties
                    </a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
