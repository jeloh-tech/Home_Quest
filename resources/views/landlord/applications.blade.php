@extends('layouts.landlord')

@section('title', 'Rental Applications')

@section('content')
<div class="fixed inset-0 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -z-10"></div>
<div class="relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Back to Properties Button - Top -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('landlord.properties') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-semibold rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Properties
            </a>

            @if($listing->isRented())
                @php
                    $acceptedApplication = $applications->where('status', 'accepted')->first();
                @endphp
                @if($acceptedApplication)
                    <form method="POST" action="{{ route('landlord.applications.terminate', $acceptedApplication->id) }}" class="inline-block">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-semibold rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                onclick="return confirm('Are you sure you want to kick this tenant? This will terminate their tenancy and make the property available again.')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Kick Tenant
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <!-- Enhanced Header Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-800 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1 mb-6 lg:mb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Rental Applications</h1>
                                <p class="text-blue-100">Manage applications for "{{ $listing->title }}"</p>
                            </div>
                        </div>

                        <!-- Property Status Card -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">₱{{ number_format($listing->price, 0) }}/month</p>
                                        <p class="text-blue-100 text-sm">{{ $listing->location }}</p>
                                    </div>
                                </div>

                                @if($listing->isRented())
                                    <div class="flex items-center space-x-2 bg-green-500/20 text-green-100 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Rented</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 bg-yellow-500/20 text-yellow-100 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Property Image Section -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <!-- Property Image Slider -->
                        <div class="relative">
                            <div class="w-48 h-36 bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden border border-white/20 shadow-lg">
                                @if($listing->images && count($listing->images) > 0)
                                    <div class="image-slider relative w-full h-full">
                                        <div class="slider-container flex transition-transform duration-300 ease-in-out" id="slider-{{ $listing->id }}">
                                            @foreach($listing->images as $image)
                                                <img src="{{ asset('storage/' . $image) }}"
                                                     alt="{{ $listing->title }}"
                                                     class="w-48 h-36 flex-shrink-0 object-cover">
                                            @endforeach
                                        </div>
                                        @if(count($listing->images) > 1)
                                            <!-- Navigation Buttons -->
                                            <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 rounded-full p-1 transition-colors duration-200" onclick="prevSlide('{{ $listing->id }}')">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                            </button>
                                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 rounded-full p-1 transition-colors duration-200" onclick="nextSlide('{{ $listing->id }}')">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                            <!-- Indicators -->
                                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
                                                @for($i = 0; $i < count($listing->images); $i++)
                                                    <button class="w-2 h-2 rounded-full bg-white/50 hover:bg-white/70 transition-colors duration-200" onclick="goToSlide('{{ $listing->id }}', {{ $i }})"></button>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <!-- Image Overlay Badge -->
                            <div class="absolute -top-2 -right-2 bg-white/90 backdrop-blur-sm text-slate-800 text-xs font-semibold px-2 py-1 rounded-full shadow-md">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ count($listing->images ?? []) }} Photo{{ count($listing->images ?? []) > 1 ? 's' : '' }}
                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if($applications->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">No tenants have applied for this property yet. Applications will appear here once tenants submit their rental applications.</p>
                <a href="{{ route('landlord.properties') }}"
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Properties
                </a>
            </div>
        @else
            <!-- Applications Grid -->
            <div class="relative">
                <!-- Move-in Date Calendar - Outside all cards -->
                @if($listing->isRented())
                    @php
                        $acceptedApplication = $applications->where('status', 'accepted')->first();
                    @endphp
                    @if($acceptedApplication)
                        @php
                            $moveInDate = \Carbon\Carbon::parse($acceptedApplication->planned_move_in_date);
                            $currentMonth = $moveInDate->copy()->startOfMonth();
                            $monthName = $moveInDate->format('F');
                            $year = $moveInDate->format('Y');
                            $daysInMonth = $moveInDate->daysInMonth;
                            $firstDayOfWeek = $currentMonth->dayOfWeek;
                            $moveInDay = $moveInDate->day;
                        @endphp
<div class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20">
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white p-6 rounded-2xl shadow-2xl border-4 border-white w-[560px] transform rotate-3 hover:rotate-0 transition-transform duration-300">
                                <!-- Calendar Header -->
                                <div class="text-center mb-3">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-bold text-sm">Move-in Calendar</span>
                                    </div>
                                    <h3 class="text-lg font-bold">{{ $monthName }} {{ $year }}</h3>
                                </div>

                                <!-- Calendar Grid -->
                                <div class="grid grid-cols-7 gap-1 text-xs">
                                    <!-- Day Headers -->
                                    <div class="text-center font-semibold text-white/80 py-1">S</div>
                                    <div class="text-center font-semibold text-white/80 py-1">M</div>
                                    <div class="text-center font-semibold text-white/80 py-1">T</div>
                                    <div class="text-center font-semibold text-white/80 py-1">W</div>
                                    <div class="text-center font-semibold text-white/80 py-1">T</div>
                                    <div class="text-center font-semibold text-white/80 py-1">F</div>
                                    <div class="text-center font-semibold text-white/80 py-1">S</div>

                                    <!-- Empty cells for days before the first day of the month -->
                                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                                        <div class="text-center py-1 text-white/30"></div>
                                    @endfor

                                    <!-- Days of the month -->
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @if($day == $moveInDay)
                                            <div class="text-center py-1 px-1 bg-yellow-400 text-emerald-800 font-bold rounded-full border-2 border-white shadow-md">
                                                {{ $day }}
                                            </div>
                                        @else
                                            <div class="text-center py-1 px-1 text-white hover:bg-white/20 rounded transition-colors duration-200">
                                                {{ $day }}
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                                <!-- Move-in Date Info -->
                                <div class="text-center mt-3 pt-3 border-t border-white/30">
                                    <div class="text-sm font-semibold opacity-90 mb-1">Move-in Date</div>
                                    <div class="text-lg font-black">
                                        {{ $moveInDate->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm font-semibold opacity-90">
                                        {{ $moveInDate->format('l') }}
                                    </div>
                                </div>

                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full border-2 border-white"></div>
                            </div>
                        </div>


                    @endif
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($applications as $application)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 group relative">

                        <!-- Application Header with Status Badge -->
                        <div class="relative">
                            <div class="bg-gradient-to-r from-slate-700 via-blue-700 to-indigo-700 text-white p-6">
                                <div class="flex items-start justify-between">
<div class="flex items-center space-x-4">
    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
    </div>
    <div class="flex-1">
        <h3 class="text-xl font-bold text-white">{{ $application->full_name }}</h3>
        <p class="text-blue-100 text-sm flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            {{ $application->email }}
        </p>
        <p class="text-blue-100 text-sm flex items-center mt-1">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            {{ $application->phone }}
        </p>
    </div>
</div>

                                    <!-- Status Badge -->
                                    <div class="flex flex-col items-end space-y-2">
                                        @if($application->status === 'pending' && !$listing->isRented())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending Review
                                            </span>
                                        @elseif($application->status === 'accepted')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Accepted
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Rejected
                                            </span>
                                        @elseif($application->status === 'cancelled')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Cancelled
                                            </span>
                                        @endif

                                        <!-- Application Date -->
                                        <p class="text-xs text-blue-100">
                                            Applied {{ $application->created_at->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="p-6">
                            <!-- Key Information Grid -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <!-- Financial Information -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-semibold text-blue-900">Financial Info</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-xs text-blue-600 font-medium">Monthly Income</p>
                                            <p class="text-lg font-bold text-blue-900">₱{{ number_format($application->monthly_income, 0) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-blue-600 font-medium">Employment</p>
                                            <p class="text-sm text-blue-800">{{ ucfirst(str_replace('_', ' ', $application->employment_status)) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Housing Information -->
                                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border border-emerald-100">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-semibold text-emerald-900">Housing Details</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-xs text-emerald-600 font-medium">Move-in Date</p>
                                            <p class="text-sm font-semibold text-emerald-900">{{ $application->planned_move_in_date ? \Carbon\Carbon::parse($application->planned_move_in_date)->format('M j, Y') : 'Not specified' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-emerald-600 font-medium">End Date</p>
                                            <p class="text-sm font-semibold text-emerald-900">{{ $application->planned_end_date ? \Carbon\Carbon::parse($application->planned_end_date)->format('M j, Y') : 'Not specified' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-emerald-600 font-medium">Occupants</p>
                                            <p class="text-sm text-emerald-800">{{ $application->occupants }} person{{ $application->occupants > 1 ? 's' : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Application Details -->
                            @if($application->reason_for_moving || $application->additional_notes)
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Application Details
                                    </h4>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        @if($application->reason_for_moving)
                                            <div>
                                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Reason for Moving</p>
                                                <p class="text-sm text-gray-900 mt-1">{{ Str::limit($application->reason_for_moving, 120) }}</p>
                                            </div>
                                        @endif
                                        @if($application->additional_notes)
                                            <div>
                                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Additional Notes</p>
                                                <p class="text-sm text-gray-900 mt-1">{{ Str::limit($application->additional_notes, 120) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Supporting Document -->
                            @if($application->document)
                                <div class="mb-6">
                                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-indigo-900">Supporting Document</p>
                                                <p class="text-xs text-indigo-600">Click to view uploaded document</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $application->document) }}"
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Section -->
                            @if($listing->isRented())
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-green-900">Property Already Rented</p>
                                            <p class="text-xs text-green-700">No further actions can be taken on this application.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if($application->status === 'pending')
                                    <!-- Action Buttons -->
                                    <div class="flex space-x-3">
                                        <form method="POST" action="{{ route('landlord.applications.accept', $application->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Accept Application
                                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('landlord.applications.reject', $application->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-semibold rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject Application
                                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($application->status === 'cancelled')
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">Application Cancelled</p>
                                                    <p class="text-xs text-gray-600">
                                                        Cancelled
                                                        @if($application->updated_at != $application->created_at)
                                                            on {{ $application->updated_at->format('M j, Y') }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if(!$listing->isRented())
                                                <form method="POST" action="{{ route('landlord.applications.remove', $application->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200"
                                                            onclick="return confirm('Are you sure you want to permanently remove this cancelled application?')">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- Status Display -->
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                        @if($listing->isRented())
                                            <form method="POST" action="{{ route('landlord.applications.terminate', $application->id) }}" class="mb-4">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-lg hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg"
                                                        onclick="return confirm('Are you sure you want to terminate this rental? This will end the tenancy and make the property available again.')">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                    </svg>
                                                    Leave Property
                                                </button>
                                            </form>
                                        @endif
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($application->status === 'accepted')
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center flex-1">
                                                    <p class="text-sm font-semibold text-green-900">Application Accepted</p>
                                                    <p class="text-xs text-green-700">
                                                        Accepted
                                                        @if($application->updated_at != $application->created_at)
                                                            on {{ $application->updated_at->format('M j, Y') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <form method="POST" action="{{ route('landlord.applications.terminate', $application->id) }}" class="ml-4">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-lg hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg"
                                                            onclick="return confirm('Are you sure you want to kick this tenant? This will terminate their tenancy and make the property available again.')">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                        </svg>
                                                        Kick Tenant
                                                    </button>
                                                </form>
                                            @elseif($application->status === 'rejected')
                                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-sm font-semibold text-red-900">Application Rejected</p>
                                                    <p class="text-xs text-red-700">
                                                        Rejected
                                                        @if($application->updated_at != $application->created_at)
                                                            on {{ $application->updated_at->format('M j, Y') }}
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $applications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function prevSlide(listingId) {
    const slider = document.getElementById('slider-' + listingId);
    const images = slider.children;
    const totalImages = images.length;
    const currentTransform = slider.style.transform || 'translateX(0px)';
    const currentTranslateX = parseInt(currentTransform.replace('translateX(', '').replace('px)', ''));
    const imageWidth = 192; // w-48 = 192px
    const newTranslateX = Math.min(currentTranslateX + imageWidth, 0);
    slider.style.transform = `translateX(${newTranslateX}px)`;
    updateIndicators(listingId, Math.abs(newTranslateX) / imageWidth);
}

function nextSlide(listingId) {
    const slider = document.getElementById('slider-' + listingId);
    const images = slider.children;
    const totalImages = images.length;
    const currentTransform = slider.style.transform || 'translateX(0px)';
    const currentTranslateX = parseInt(currentTransform.replace('translateX(', '').replace('px)', ''));
    const imageWidth = 192; // w-48 = 192px
    const maxTranslateX = -(totalImages - 1) * imageWidth;
    const newTranslateX = Math.max(currentTranslateX - imageWidth, maxTranslateX);
    slider.style.transform = `translateX(${newTranslateX}px)`;
    updateIndicators(listingId, Math.abs(newTranslateX) / imageWidth);
}

function goToSlide(listingId, slideIndex) {
    const slider = document.getElementById('slider-' + listingId);
    const imageWidth = 192; // w-48 = 192px
    const translateX = -slideIndex * imageWidth;
    slider.style.transform = `translateX(${translateX}px)`;
    updateIndicators(listingId, slideIndex);
}

function updateIndicators(listingId, activeIndex) {
    const sliderContainer = document.getElementById('slider-' + listingId);
    if (!sliderContainer) return;
    const indicators = sliderContainer.parentElement.querySelectorAll('.w-2.h-2');
    indicators.forEach((indicator, index) => {
        if (index === activeIndex) {
            indicator.classList.remove('bg-white/50');
            indicator.classList.add('bg-white/70');
        } else {
            indicator.classList.remove('bg-white/70');
            indicator.classList.add('bg-white/50');
        }
    });
}
</script>
@endsection
