@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <div class="flex-1 min-h-screen ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Professional Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $landlords->total() }} Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($landlords->count() > 0)
                <!-- Enhanced Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Pending</p>
                                <p class="text-3xl font-bold">{{ $landlords->total() }}</p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-emerald-100 text-sm font-medium">This Week</p>
                                <p class="text-3xl font-bold">{{ $landlords->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                            </div>
                            <div class="bg-emerald-400 bg-opacity-30 rounded-lg p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-violet-500 to-violet-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-violet-100 text-sm font-medium">Verified Today</p>
                                <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'landlord')->where('verification_status', 'approved')->whereDate('verified_at', today())->count() }}</p>
                            </div>
                            <div class="bg-violet-400 bg-opacity-30 rounded-lg p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-100 text-sm font-medium">Avg. Processing</p>
                                <p class="text-3xl font-bold">2.4h</p>
                            </div>
                            <div class="bg-amber-400 bg-opacity-30 rounded-lg p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Card Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($landlords as $landlord)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500 transform hover:-translate-y-3 group relative overflow-hidden">
                        <!-- Subtle background animation -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <div class="relative p-6">
                            <!-- Enhanced Header -->
                            <div class="flex items-start justify-between mb-5">
                                <div class="flex items-center">
                                    <div class="h-14 w-14 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center mr-4 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                        <span class="text-white font-bold text-xl">{{ substr($landlord->name, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 leading-tight">{{ $landlord->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">ID: {{ $landlord->getKey() }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-sm animate-pulse">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0 1 1 0 012 0zm-1 4a1 1 0 00-1 1v4a1 1 0 102 0V11a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pending
                                    </span>
                                </div>
                            </div>

                            <!-- Enhanced Contact Info -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-sm bg-gray-50 rounded-lg p-3.5 hover:bg-blue-50 transition-colors duration-200 group-hover:shadow-sm">
                                    <div class="bg-blue-100 rounded-full p-2.5 mr-3 group-hover:bg-blue-200 transition-colors duration-200">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Email</p>
                                        <p class="text-gray-900 truncate font-medium">{{ $landlord->email }}</p>
                                    </div>
                                </div>

                                @if($landlord->phone)
                                <div class="flex items-center text-sm bg-gray-50 rounded-lg p-3.5 hover:bg-green-50 transition-colors duration-200 group-hover:shadow-sm">
                                    <div class="bg-green-100 rounded-full p-2.5 mr-3 group-hover:bg-green-200 transition-colors duration-200">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Phone</p>
                                        <p class="text-gray-900 font-medium">{{ $landlord->phone }}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center text-sm bg-gray-50 rounded-lg p-3.5 hover:bg-purple-50 transition-colors duration-200 group-hover:shadow-sm">
                                    <div class="bg-purple-100 rounded-full p-2.5 mr-3 group-hover:bg-purple-200 transition-colors duration-200">
                                        <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Joined</p>
                                        <p class="text-gray-900 font-medium">{{ $landlord->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Action Button -->
                            <button data-user-id="{{ $landlord->getKey() }}" data-user-type="landlord" onclick="openVerificationModal(this.dataset.userId, this.dataset.userType)"
                                    class="w-full inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl group-hover:shadow-2xl relative overflow-hidden">
                                <!-- Button ripple effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <svg class="h-5 w-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="relative z-10">Review Application</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                @if($landlords->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        {{ $landlords->links() }}
                    </div>
                </div>
                @endif
            @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto h-32 w-32 text-gray-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-2xl font-bold text-gray-900">All caught up!</h3>
                    <p class="mt-2 text-lg text-gray-600">No pending landlord verifications at the moment.</p>
                    <div class="mt-6">
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Great work team!
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>



@endsection

<!-- Include verification JavaScript -->
<script src="{{ asset('js/verification.js') }}"></script>
