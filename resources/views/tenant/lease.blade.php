@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Lease Agreement</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">View your rental lease details</p>
            </div>

            @if($rentalListing)
            <!-- Lease Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Lease Agreement Details</h2>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </button>
                        <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Property Information -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Property Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Property Title</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $rentalListing->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                            <p class="text-gray-900 dark:text-white">{{ $rentalListing->location }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Rent</p>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400">${{ number_format($rentalListing->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Lease Start Date</p>
                            <p class="text-gray-900 dark:text-white">{{ $rentalListing->lease_start_date ? $rentalListing->lease_start_date->format('M d, Y') : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Parties Involved -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Parties Involved</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Landlord</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $rentalListing->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rentalListing->user->email }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rentalListing->user->phone ?? 'Phone not provided' }}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Tenant</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->phone ?? 'Phone not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lease Terms -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Lease Terms</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Lease Period</p>
                                <p class="text-gray-900 dark:text-white">{{ $rentalListing->lease_start_date ? $rentalListing->lease_start_date->format('M d, Y') : 'Not specified' }} - {{ $rentalListing->available_to ? $rentalListing->available_to->format('M d, Y') : 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Rent</p>
                                <p class="text-gray-900 dark:text-white">${{ number_format($rentalListing->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Security Deposit</p>
                                <p class="text-gray-900 dark:text-white">${{ number_format($rentalListing->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Property Description</p>
                            <p class="text-gray-900 dark:text-white">{{ $rentalListing->description }}</p>
                        </div>
                        @if($rentalListing->amenities)
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Amenities</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($rentalListing->amenities as $amenity)
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs rounded-full">{{ $amenity }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Terms and Conditions</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">1. Payment Terms</h4>
                                <p>Rent is due on the 1st of each month. Late payments may incur a fee of $50 per day after the 5th.</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">2. Maintenance Responsibilities</h4>
                                <p>Tenant is responsible for minor repairs under $100. Major repairs should be reported to the landlord immediately.</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">3. Termination</h4>
                                <p>Either party may terminate this lease with 30 days written notice. Early termination may result in penalties.</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">4. Property Use</h4>
                                <p>The property shall be used only as a private residence. No subletting without written landlord approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="flex justify-start">
                <a href="{{ route('tenant.rental') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to My Rental
                </a>
            </div>
            @else
            <!-- No Active Rental Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Active Lease</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">You don't have any active lease agreements at the moment.</p>
                    <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse Properties
                    </a>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
