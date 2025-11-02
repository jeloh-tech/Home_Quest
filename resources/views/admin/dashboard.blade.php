@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <!-- Main Content Area -->
    <main class="ml-64 flex-1 p-8 overflow-y-auto">
        @include('components.login-success-modal')

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Verifications Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Verifications</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($pendingVerifications) }}</p>
                        <p class="text-sm text-orange-600 mt-1">
                            <span class="inline-flex items-center">
                                Requires attention
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Maintenance Requests Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Maintenance Requests</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($maintenanceRequests) }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <span class="inline-flex items-center">
                                Pending resolution
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>



            <!-- Total Tenants Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tenants</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalTenants) }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                +8% from last month
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Landlords Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Landlords</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalLandlords) }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                +15% from last month
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Admins Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Admins</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAdmins) }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                +5% from last month
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Posts Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Posts</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPosts) }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                +23 new this week
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v3H7V8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Posts Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Posts</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($activePosts) }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <span class="inline-flex items-center">
                                {{ number_format($activePostsPercentage) }}% of total posts
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rented Properties Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rented Properties</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($rentedProperties) }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <span class="inline-flex items-center">
                                {{ number_format($rentedPropertiesPercentage) }}% occupancy rate
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>



        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Registration Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registration Trend</h3>
                <canvas id="userRegistrationChart" width="400" height="200"></canvas>
            </div>

            <!-- Listing Status Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Listing Status Distribution</h3>
                <canvas id="listingStatusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue and Top Landlords Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8 mb-8">
            <!-- Monthly Payments Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
                <canvas id="monthlyPaymentsChart" width="400" height="200"></canvas>
            </div>

            <!-- Top Landlords Section -->
            @if($topLandlords->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Performing Landlords</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Landlord</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properties</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topLandlords as $landlord)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($landlord->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $landlord->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $landlord->listings_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $landlord->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($landlord->verification_status === 'approved') bg-green-100 text-green-800
                                    @elseif($landlord->verification_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($landlord->verification_status ?? 'pending') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No landlords found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($topLandlords->hasPages())
                <div class="mt-4">
                    {{ $topLandlords->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
        @endif

        <!-- Recent Payments Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Payments</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ substr($payment->tenant->name ?? 'N/A', 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->tenant->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->tenant->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ Str::limit($payment->listing->title ?? 'N/A', 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($payment->status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                    @elseif($payment->status === 'refunded') bg-gray-100 text-gray-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent payments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Alerts Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">System Alerts</h2>
            <div class="space-y-4">
                @if($pendingVerifications > 0)
                <div class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-orange-800">
                            {{ $pendingVerifications }} pending user verification{{ $pendingVerifications > 1 ? 's' : '' }}
                        </p>
                        <p class="text-sm text-orange-700">
                            Review and approve pending user registrations to maintain platform security.
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('admin.verification.landlords') }}" class="text-sm font-medium text-orange-800 hover:text-orange-600">
                            Review Now →
                        </a>
                    </div>
                </div>
                @endif

                @if($maintenanceRequests > 0)
                <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ $maintenanceRequests }} pending maintenance request{{ $maintenanceRequests > 1 ? 's' : '' }}
                        </p>
                        <p class="text-sm text-red-700">
                            Address maintenance issues to ensure tenant satisfaction and property quality.
                        </p>
                    </div>
                    <div class="ml-auto">
                        <span class="text-sm font-medium text-red-800 cursor-not-allowed opacity-50">
                            View Requests →
                        </span>
                    </div>
                </div>
                @endif

                @if($disputedPayments > 0)
                <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">
                            {{ $disputedPayments }} disputed payment{{ $disputedPayments > 1 ? 's' : '' }}
                        </p>
                        <p class="text-sm text-yellow-700">
                            Review disputed payments and resolve conflicts between tenants and landlords.
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('admin.payment-history') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-600">
                            Review Disputes →
                        </a>
                    </div>
                </div>
                @endif

                @if($bannedUsers > 0)
                <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $bannedUsers }} banned user{{ $bannedUsers > 1 ? 's' : '' }}
                        </p>
                        <p class="text-sm text-gray-700">
                            Monitor banned accounts and consider unbanning if appropriate.
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('admin.users') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">
                            Manage Users →
                        </a>
                    </div>
                </div>
                @endif

                @if($pendingVerifications == 0 && $maintenanceRequests == 0 && $disputedPayments == 0 && $bannedUsers == 0)
                <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            All systems operational
                        </p>
                        <p class="text-sm text-green-700">
                            No immediate action required. Platform is running smoothly.
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pass PHP data to JavaScript
    window.chartData = {
        monthlyUsers: @json($monthlyUsers ?? []),
        listingStatusData: @json($listingStatusData ?? []),
        monthlyPayments: @json($monthlyPayments ?? [])
    };

    // Restore scroll position on page load
    document.addEventListener('DOMContentLoaded', function() {
        const mainElement = document.querySelector('main');
        const savedScrollPercentage = sessionStorage.getItem('adminDashboardScrollPosition');
        if (savedScrollPercentage && mainElement) {
            // Use setTimeout to ensure content is fully rendered
            setTimeout(function() {
                const percentage = parseFloat(savedScrollPercentage);
                if (!isNaN(percentage)) {
                    const maxScroll = mainElement.scrollHeight - mainElement.clientHeight;
                    mainElement.scrollTop = (percentage / 100) * maxScroll;
                }
            }, 100);
        }

        // Save scroll position on scroll events
        let scrollTimeout;
        if (mainElement) {
            mainElement.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    const scrollPercentage = (mainElement.scrollTop / (mainElement.scrollHeight - mainElement.clientHeight)) * 100;
                    sessionStorage.setItem('adminDashboardScrollPosition', scrollPercentage.toString());
                }, 100); // Debounce scroll events
            });
        }

        // Save scroll position before navigation
        const navigationLinks = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"])');
        navigationLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (mainElement) {
                    const scrollPercentage = (mainElement.scrollTop / (mainElement.scrollHeight - mainElement.clientHeight)) * 100;
                    sessionStorage.setItem('adminDashboardScrollPosition', scrollPercentage.toString());
                }
            });
        });

        // Save scroll position on form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                if (mainElement) {
                    const scrollPercentage = (mainElement.scrollTop / (mainElement.scrollHeight - mainElement.clientHeight)) * 100;
                    sessionStorage.setItem('adminDashboardScrollPosition', scrollPercentage.toString());
                }
            });
        });

        // Save scroll position before page unload
        window.addEventListener('beforeunload', function() {
            if (mainElement) {
                const scrollPercentage = (mainElement.scrollTop / (mainElement.scrollHeight - mainElement.clientHeight)) * 100;
                sessionStorage.setItem('adminDashboardScrollPosition', scrollPercentage.toString());
            }
        });
    });
</script>
@vite(['resources/js/admin-dashboard-charts.js'])
@endsection
