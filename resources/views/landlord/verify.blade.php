@extends('layouts.landlord')

@section('content')
<style>
/* Override the main layout background and padding for this page */
main {
    background: transparent !important;
    padding: 0 !important;
}
</style>
<div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50 -z-10"></div>
<div class="relative z-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Account Verification</h1>
            <p class="text-lg text-gray-600">Complete your verification to start posting properties</p>
        </div>
                
        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-red-900 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Errors Display -->
        <div id="formErrors" class="hidden bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 00-1.414 1.414L10 11.414l1.293 1.293a1 1 0 01-1.414 1.414L10 11.414l-1.293-1.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Please fix the following errors:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul id="errorList" role="list" class="list-disc pl-5 space-y-1">
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Status Messages -->
        @if(auth()->user()->verification_status === 'approved')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-8 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-bold text-green-900">🎉 Verification Approved!</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Verified</span>
                        </div>
                        <p class="text-green-700 mb-4">Your account is fully verified and ready to go! You can now create and manage property listings with confidence.</p>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('landlord.add-post') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Your First Listing
                            </a>
                            <a href="{{ route('landlord.properties') }}" class="inline-flex items-center px-4 py-3 border border-green-300 text-green-700 bg-white text-sm font-medium rounded-lg hover:bg-green-50 transition-colors">
                                View My Properties
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->verification_status === 'declined' || auth()->user()->verification_status === 'rejected')
            <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-6 mb-8 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-bold text-red-900">
                                @if(strpos(auth()->user()->verification_notes ?? '', 'Reset - No documents submitted') !== false)
                                    Verification Reset - No Documents Found
                                @else
                                    Verification Needs Attention
                                @endif
                            </h3>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                                @if(strpos(auth()->user()->verification_notes ?? '', 'Reset - No documents submitted') !== false)
                                    Reset - Action Required
                                @else
                                    Action Required
                                @endif
                            </span>
                        </div>
                        <p class="text-red-700 mb-4">
                            @if(strpos(auth()->user()->verification_notes ?? '', 'Reset - No documents submitted') !== false)
                                Your verification status was reset because no documents were found in our system. This usually happens when verification was set manually without proper document submission. Please submit your verification documents to get started.
                            @else
                                {{ auth()->user()->verification_notes ?? 'Your verification documents need to be updated. Please review the requirements and resubmit.' }}
                            @endif
                        </p>
                        <div class="flex items-center space-x-3">
                            <button onclick="proceedToVerification()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-semibold rounded-lg hover:from-red-700 hover:to-rose-700 transition-all shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15m-4-4v4m0 0v4m0-4h4m-4 0H9"></path>
                                </svg>
                                @if(strpos(auth()->user()->verification_notes ?? '', 'Reset - No documents submitted') !== false)
                                    Submit Documents
                                @else
                                    Resubmit Documents
                                @endif
                            </button>
                            <a href="mailto:support@yourplatform.com" class="inline-flex items-center px-4 py-3 border border-red-300 text-red-700 bg-white text-sm font-medium rounded-lg hover:bg-red-50 transition-colors">
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @endif



        @if(($documentType && $documentType !== 'not_specified') || ($verificationDocuments && count($verificationDocuments) > 0))
        <!-- Current Verification Documents -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Current Verification Documents
                </h3>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if(auth()->user()->verification_status === 'pending') bg-blue-100 text-blue-800
                        @elseif(auth()->user()->verification_status === 'declined' || auth()->user()->verification_status === 'rejected') bg-red-100 text-red-800
                        @elseif(auth()->user()->verification_status === 'approved') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if(auth()->user()->verification_status === 'pending') Under Review
                        @elseif(auth()->user()->verification_status === 'declined' || auth()->user()->verification_status === 'rejected') Requires Update
                        @elseif(auth()->user()->verification_status === 'approved') Verified
                        @else Submitted @endif
                    </span>
                </div>
            </div>

            @if(auth()->user()->verification_status === 'pending')
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-800">Your documents are currently under review by our team. You can view them below while the verification process is ongoing.</p>
                </div>
            </div>
            @endif

            @php
                // Map document types to proper display names
                $docTypeLabels = [
                    'philippine_id' => 'Philippine ID (PhilID)',
                    'drivers_license' => 'Driver\'s License',
                    'sss_gsis' => 'SSS/GSIS ID',
                    'passport' => 'Passport',
                    'birth_certificate' => 'Birth Certificate',
                    'other' => 'Other Valid ID'
                ];

                $displayDocType = isset($docTypeLabels[$documentType]) ? $docTypeLabels[$documentType] : ($documentType ? ucfirst(str_replace('_', ' ', $documentType)) : 'Not specified');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(isset($verificationDocuments['front']))
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Front Side of ID</h4>
                    <div class="aspect-w-3 aspect-h-2 bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ $verificationDocuments['front'] }}"
                             alt="Front ID"
                             class="w-full h-full object-cover"
                             onerror="this.src='/images/placeholder-id.png'">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Document Type: {{ $displayDocType }}</p>
                </div>
                @elseif($documentType)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Front Side of ID</h4>
                    <div class="aspect-w-3 aspect-h-2 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">Document uploaded but not currently available for preview</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Document Type: {{ $displayDocType }}</p>
                </div>
                @endif

                @if(isset($verificationDocuments['back']))
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Back Side of ID</h4>
                    <div class="aspect-w-3 aspect-h-2 bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ $verificationDocuments['back'] }}"
                             alt="Back ID"
                             class="w-full h-full object-cover"
                             onerror="this.src='/images/placeholder-id.png'">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Document Type: {{ $displayDocType }}</p>
                </div>
                @elseif($documentType && auth()->user()->valid_id_back_path)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Back Side of ID</h4>
                    <div class="aspect-w-3 aspect-h-2 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">Document uploaded but not currently available for preview</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Document Type: {{ $displayDocType }}</p>
                </div>
                @endif
            </div>

            @if(auth()->user()->verification_status === 'declined' && auth()->user()->verification_notes)
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h4 class="font-medium text-red-900 mb-2">Review Notes:</h4>
                <p class="text-red-800 text-sm">{{ auth()->user()->verification_notes }}</p>
            </div>
            @endif
        </div>

        @endif

        {{-- Removed duplicate "Account Verification Notice" section to eliminate redundant "Update Documents" functionality --}}

        @if(auth()->user()->verification_status !== 'approved')
        <!-- Main Verification Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" id="verificationFormContainer">
            <!-- Progress Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h2 class="text-xl font-semibold">Submit Verification Documents</h2>
                        <p class="text-blue-100 mt-1">Complete all required fields to get verified</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1">
                            <!-- Step 1 -->
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center transition-all duration-300" id="step1Indicator">
                                <span class="text-sm font-semibold text-blue-600">1</span>
                            </div>
                            <div class="w-6 h-0.5 bg-white bg-opacity-30"></div>
                            <!-- Step 2 -->
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center transition-all duration-300" id="step2Indicator">
                                <span class="text-sm font-semibold text-white">2</span>
                            </div>
                        </div>
                        <span class="text-sm" id="stepText">Step 1 of 2</span>
                    </div>
                </div>
            </div>

            <form id="verificationForm" action="{{ route('landlord.submitVerification') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <!-- Step 1: Document Type Selection -->
                <div class="step-content" id="step1Content">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                            Select Document Type
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="documentTypeGrid">
                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="philippine_id" class="sr-only peer" required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-blue-200">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Philippine ID (PhilID)</div>
                                            <div class="text-sm text-gray-600">National ID card</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="drivers_license" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-green-200">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Driver's License</div>
                                            <div class="text-sm text-gray-600">Professional/Non-professional</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="sss_gsis" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-purple-200">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">SSS/GSIS ID</div>
                                            <div class="text-sm text-gray-600">Social Security ID</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="passport" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-indigo-200">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Passport</div>
                                            <div class="text-sm text-gray-600">International passport</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="birth_certificate" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-pink-500 peer-checked:bg-pink-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-pink-200">
                                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Birth Certificate</div>
                                            <div class="text-sm text-gray-600">PSA birth certificate</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative document-type-option">
                                <input type="radio" name="document_type" value="other" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-gray-500 peer-checked:bg-gray-50 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 peer-checked:bg-gray-200">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Other Valid ID</div>
                                            <div class="text-sm text-gray-600">Postal ID, PRC, etc.</div>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Next Button for Step 1 -->
                        <div class="mt-8 flex justify-end">
                            <button type="button" id="nextToStep2" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <span>Next: Upload Documents</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Document Upload -->
                <div class="step-content hidden" id="step2Content">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                            Upload Document Images
                        </h3>

                        <!-- Front Side -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Front Side of ID <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-all duration-200" id="frontDropZone">
                                <input type="file" name="valid_id_front" id="valid_id_front" accept="image/*" class="hidden" required>
                                <div id="frontUploadArea" class="upload-area">
                                    <div class="space-y-4">
                                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                        </div>
                                        <button type="button" onclick="document.getElementById('valid_id_front').click()" id="frontChooseBtn" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Choose File
                                        </button>
                                        <button type="button" onclick="removeFrontImage()" id="frontRemoveBtn" class="hidden px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors ml-2">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <div id="frontPreview" class="hidden mt-4">
                                    <img id="frontImage" class="max-w-full h-auto max-h-48 mx-auto rounded-lg shadow-md">
                                    <button type="button" onclick="removeFrontImage()" class="mt-2 px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition-colors">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Back Side -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Back Side of ID <span class="text-gray-500">(Optional)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-all duration-200" id="backDropZone">
                                <input type="file" name="valid_id_back" id="valid_id_back" accept="image/*" class="hidden">
                                <div id="backUploadArea" class="upload-area">
                                    <div class="space-y-4">
                                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                        </div>
                                        <button type="button" onclick="document.getElementById('valid_id_back').click()" id="backChooseBtn" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                            Choose File
                                        </button>
                                        <button type="button" onclick="removeBackImage()" id="backRemoveBtn" class="hidden px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors ml-2">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <div id="backPreview" class="hidden mt-4">
                                    <img id="backImage" class="max-w-full h-auto max-h-48 mx-auto rounded-lg shadow-md">
                                    <button type="button" onclick="removeBackImage()" class="mt-2 px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition-colors">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-8">
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Information
                            </label>
                            <textarea name="verification_notes" id="verification_notes" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-colors"
                                      placeholder="Any additional information that might help with verification (optional)"></textarea>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-8">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" name="terms" value="1" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors">
                                <label for="terms" class="ml-3 text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-blue-600 hover:text-blue-800 underline">Terms of Service</a> and
                                    <a href="#" class="text-blue-600 hover:text-blue-800 underline">Privacy Policy</a>. I understand that my documents will be used solely for verification purposes.
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <button type="button" id="backToStep1" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </button>
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Need help?</p>
                                <p>Contact our support team for assistance</p>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Submit for Verification
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <!-- Help Section -->
        <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Guidelines</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Accepted Documents
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Philippine ID (PhilID)</li>
                        <li>• Driver's License</li>
                        <li>• SSS/GSIS ID</li>
                        <li>• Passport</li>
                        <li>• Birth Certificate</li>
                        <li>• Postal ID</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Photo Requirements
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Clear, well-lit photos</li>
                        <li>• All corners visible</li>
                        <li>• No glare or shadows</li>
                        <li>• Maximum 5MB per image</li>
                        <li>• PNG, JPG, or JPEG format</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File upload preview functionality
document.getElementById('valid_id_front').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('frontImage').src = e.target.result;
            document.getElementById('frontPreview').classList.remove('hidden');
            document.getElementById('frontUploadArea').classList.add('hidden');
            document.getElementById('frontChooseBtn').classList.add('hidden');
            document.getElementById('frontRemoveBtn').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('valid_id_back').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('backImage').src = e.target.result;
            document.getElementById('backPreview').classList.remove('hidden');
            document.getElementById('backUploadArea').classList.add('hidden');
            document.getElementById('backChooseBtn').classList.add('hidden');
            document.getElementById('backRemoveBtn').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Drag and drop functionality
['frontDropZone', 'backDropZone'].forEach(zoneId => {
    const dropZone = document.getElementById(zoneId);
    const inputId = zoneId === 'frontDropZone' ? 'valid_id_front' : 'valid_id_back';

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            document.getElementById(inputId).files = files;
            document.getElementById(inputId).dispatchEvent(new Event('change'));
        }
    }
});

// Function to proceed to verification form
function proceedToVerification() {
    const form = document.getElementById('verificationFormContainer');

    if (form) {
        // Show the form with fade in effect
        form.style.display = 'block';
        form.style.opacity = '0';
        form.style.transition = 'opacity 0.3s ease-in';

        setTimeout(() => {
            form.style.opacity = '1';
            // Smooth scroll to the form
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 50);
    } else {
        console.error('Verification form container not found');
    }
}

// Multi-step form navigation
document.getElementById('nextToStep2').addEventListener('click', function() {
    const selectedDocType = document.querySelector('input[name="document_type"]:checked');
    if (!selectedDocType) {
        alert('Please select a document type before proceeding.');
        return;
    }

    // Update step indicators
    document.getElementById('step1Indicator').classList.remove('bg-white');
    document.getElementById('step1Indicator').classList.add('bg-blue-100');
    document.getElementById('step1Indicator').innerHTML = '<span class="text-sm font-semibold text-blue-600">✓</span>';

    document.getElementById('step2Indicator').classList.remove('bg-white', 'bg-opacity-20');
    document.getElementById('step2Indicator').classList.add('bg-white');
    document.getElementById('step2Indicator').innerHTML = '<span class="text-sm font-semibold text-blue-600">2</span>';

    document.getElementById('stepText').textContent = 'Step 2 of 2';

    // Hide step 1, show step 2
    document.getElementById('step1Content').classList.add('hidden');
    document.getElementById('step2Content').classList.remove('hidden');
});

document.getElementById('backToStep1').addEventListener('click', function() {
    // Update step indicators
    document.getElementById('step1Indicator').classList.remove('bg-blue-100');
    document.getElementById('step1Indicator').classList.add('bg-white');
    document.getElementById('step1Indicator').innerHTML = '<span class="text-sm font-semibold text-blue-600">1</span>';

    document.getElementById('step2Indicator').classList.remove('bg-white');
    document.getElementById('step2Indicator').classList.add('bg-white', 'bg-opacity-20');
    document.getElementById('step2Indicator').innerHTML = '<span class="text-sm font-semibold text-white">2</span>';

    document.getElementById('stepText').textContent = 'Step 1 of 2';

    // Hide step 2, show step 1
    document.getElementById('step2Content').classList.add('hidden');
    document.getElementById('step1Content').classList.remove('hidden');
});

// Image removal functions
function removeFrontImage() {
    document.getElementById('valid_id_front').value = '';
    document.getElementById('frontPreview').classList.add('hidden');
    document.getElementById('frontUploadArea').classList.remove('hidden');
    document.getElementById('frontChooseBtn').classList.remove('hidden');
    document.getElementById('frontRemoveBtn').classList.add('hidden');
    document.getElementById('frontImage').src = '';
}

function removeBackImage() {
    document.getElementById('valid_id_back').value = '';
    document.getElementById('backPreview').classList.add('hidden');
    document.getElementById('backUploadArea').classList.remove('hidden');
    document.getElementById('backChooseBtn').classList.remove('hidden');
    document.getElementById('backRemoveBtn').classList.add('hidden');
    document.getElementById('backImage').src = '';
}

// Form validation
document.getElementById('verificationForm').addEventListener('submit', function(e) {
    console.log('Form submission started');

    const documentType = document.querySelector('input[name="document_type"]:checked');
    const frontFile = document.getElementById('valid_id_front').files[0];
    const backFile = document.getElementById('valid_id_back').files[0];
    const termsCheckbox = document.getElementById('terms');
    const terms = termsCheckbox ? termsCheckbox.checked : false;

    console.log('Document type:', documentType ? documentType.value : 'none');
    console.log('Front file:', frontFile ? 'selected' : 'none');
    console.log('Back file:', backFile ? 'selected' : 'none');
    console.log('Terms checked:', terms);

    let errors = [];

    if (!documentType) {
        errors.push('Please select a document type.');
    }

    if (!frontFile) {
        errors.push('Please upload the front side of your ID.');
    } else if (frontFile.size > 10 * 1024 * 1024) { // 10MB limit
        errors.push('Front ID file size must be less than 10MB.');
    }

    if (backFile && backFile.size > 10 * 1024 * 1024) { // 10MB limit for back file if uploaded
        errors.push('Back ID file size must be less than 10MB.');
    }

    if (!terms) {
        errors.push('Please agree to the terms and conditions.');
    }

    if (errors.length > 0) {
        e.preventDefault();

        const errorList = document.getElementById('errorList');
        errorList.innerHTML = '';

        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });

        document.getElementById('formErrors').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });

        return;
    }

    // Hide any previous errors
    document.getElementById('formErrors').classList.add('hidden');

    // Ensure terms checkbox value is set
    if (termsCheckbox && terms) {
        termsCheckbox.value = '1';
    }

    console.log('Form validation passed, submitting...');

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Submitting...';
});
</script>
@endsection
