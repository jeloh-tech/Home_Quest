@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 analytics-dashboard">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <!-- Main Content Area -->
    <main class="ml-64 flex-1 p-8 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg fade-in">
                <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold text-gray-900 mb-6 fade-in">Analytics Dashboard</h1>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="analytics-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-3xl font-bold text-gray-900 counter-value">{{ number_format($totalUsers) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg icon-container">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Tenants</p>
                                <p class="text-3xl font-bold text-gray-900 counter-value">{{ number_format($totalTenants) }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg icon-container">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Landlords</p>
                                <p class="text-3xl font-bold text-gray-900 counter-value">{{ number_format($totalLandlords) }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-lg icon-container">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Posts</p>
                                <p class="text-3xl font-bold text-gray-900 counter-value">{{ number_format($totalListings) }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-lg icon-container">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v3H7V8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Analytics Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8 fade-in-delayed">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Reports Analytics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div class="analytics-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Reports</p>
                                    <p class="text-3xl font-bold text-gray-900 counter-value">{{ number_format($reportStats['total_reports']) }}</p>
                                </div>
                                <div class="p-3 bg-red-100 rounded-lg icon-container">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Pending Reports</p>
                                    <p class="text-3xl font-bold text-yellow-600 counter-value">{{ number_format($reportStats['pending_reports']) }}</p>
                                </div>
                                <div class="p-3 bg-yellow-100 rounded-lg icon-container">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Reviewed Reports</p>
                                    <p class="text-3xl font-bold text-blue-600 counter-value">{{ number_format($reportStats['reviewed_reports']) }}</p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-lg icon-container">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Resolved Reports</p>
                                    <p class="text-3xl font-bold text-green-600 counter-value">{{ number_format($reportStats['resolved_reports']) }}</p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-lg icon-container">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Recent Reports (7 days)</p>
                                    <p class="text-3xl font-bold text-purple-600 counter-value">{{ number_format($reportStats['recent_reports']) }}</p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-lg icon-container">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Distribution</h3>
                        <div class="h-64 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-blue-600">{{ $totalUsers }}</div>
                                <div class="text-sm text-gray-600">Total Users</div>
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm">
                                        <span>Tenants: {{ $totalTenants }}</span>
                                        <span>{{ number_format($totalUsers > 0 ? ($totalTenants / $totalUsers) * 100 : 0) }}%</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Landlords: {{ $totalLandlords }}</span>
                                        <span>{{ number_format($totalUsers > 0 ? ($totalLandlords / $totalUsers) * 100 : 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Status</h3>
                        <div class="h-64 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-green-600">{{ $totalListings }}</div>
                                <div class="text-sm text-gray-600">Total Properties</div>
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm">
                                        <span>Active: {{ $activePosts }}</span>
                                        <span>{{ number_format($totalListings > 0 ? ($activePosts / $totalListings) * 100 : 0) }}%</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Rented: {{ $totalListings - $activePosts }}</span>
                                        <span>{{ number_format($totalListings > 0 ? (($totalListings - $activePosts) / $totalListings) * 100 : 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-slow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.reports.export') }}?type=users" class="export-btn inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition-all duration-200">
                            Export Users Report
                        </a>
                        <a href="{{ route('admin.reports.export') }}?type=properties" class="export-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition-all duration-200">
                            Export Properties Report
                        </a>
                        <a href="{{ route('admin.reports.export') }}?type=analytics" class="export-btn inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition-all duration-200">
                            Export Analytics Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>
@endsection
