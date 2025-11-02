@extends('layouts.app')

@section('title', 'Apply for Rental - ' . $listing->title)

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Rental Application</h1>
                    <p class="mt-2 text-gray-600">Apply for: <span class="font-semibold">{{ $listing->title }}</span></p>
                </div>
                <a href="{{ route('tenant.property-details', $listing->id) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Property
                </a>
            </div>
        </div>

        <!-- Property Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img src="{{ $listing->images ? asset('storage/' . $listing->images[0]) : asset('storage/img/homess.png') }}"
                         alt="{{ $listing->title }}"
                         class="w-full h-48 object-cover rounded-lg">
                </div>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Monthly Rent</span>
                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($listing->price, 2) }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Bedrooms</span>
                            <p class="font-semibold">{{ $listing->room_count }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Bathrooms</span>
                            <p class="font-semibold">{{ $listing->bathroom_count }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Location</span>
                        <p class="font-semibold">{{ $listing->location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Application Information</h2>

            <form action="{{ route('tenant.rental-application.store', $listing) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    </div>

                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', auth()->user()->name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="planned_move_in_date" class="block text-sm font-medium text-gray-700">Planned Move-in Date *</label>
                        <input type="date" name="planned_move_in_date" id="planned_move_in_date" value="{{ old('planned_move_in_date') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('planned_move_in_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_duration" class="block text-sm font-medium text-gray-700">Rental Duration *</label>
                        <select name="rental_duration" id="rental_duration"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <option value="">Select duration</option>
                            <option value="1" {{ old('rental_duration') == '1' ? 'selected' : '' }}>1 Month</option>
                            <option value="3" {{ old('rental_duration') == '3' ? 'selected' : '' }}>3 Months</option>
                            <option value="6" {{ old('rental_duration') == '6' ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ old('rental_duration') == '12' ? 'selected' : '' }}>1 Year</option>
                            <option value="18" {{ old('rental_duration') == '18' ? 'selected' : '' }}>18 Months</option>
                            <option value="24" {{ old('rental_duration') == '24' ? 'selected' : '' }}>2 Years</option>
                        </select>
                        @error('rental_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="planned_end_date" class="block text-sm font-medium text-gray-700">Calculated End Date</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="date" name="planned_end_date" id="planned_end_date" value="{{ old('planned_end_date') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed sm:text-sm"
                                   readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">End date is automatically calculated based on your move-in date and selected duration</p>
                        @error('planned_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Employment Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Employment & Financial Information</h3>
                    </div>

                    <div>
                        <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status *</label>
                        <select name="employment_status" id="employment_status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <option value="">Select employment status</option>
                            <option value="employed" {{ old('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                            <option value="self-employed" {{ old('employment_status') == 'self-employed' ? 'selected' : '' }}>Self-employed</option>
                            <option value="student" {{ old('employment_status') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="unemployed" {{ old('employment_status') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            <option value="retired" {{ old('employment_status') == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('employment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="monthly_income" class="block text-sm font-medium text-gray-700">Monthly Income (₱) *</label>
                        <input type="number" name="monthly_income" id="monthly_income" value="{{ old('monthly_income') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="0.00" step="0.01" min="0" required>
                        @error('monthly_income')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupants" class="block text-sm font-medium text-gray-700">Number of Occupants *</label>
                        <input type="number" name="occupants" id="occupants" value="{{ old('occupants', 1) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               min="1" required>
                        @error('occupants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="reason_for_moving" class="block text-sm font-medium text-gray-700">Reason for Moving *</label>
                        <textarea name="reason_for_moving" id="reason_for_moving" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Please explain why you are looking to move..." required>{{ old('reason_for_moving') }}</textarea>
                        @error('reason_for_moving')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="additional_notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                        <textarea name="additional_notes" id="additional_notes" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Any additional information you'd like to share...">{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="document" class="block text-sm font-medium text-gray-700">Supporting Document (Optional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="document" name="document" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG up to 5MB</p>
                            </div>
                        </div>
                        @error('document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Submit Application
                    </button>
                </div>
            </form>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moveInDateInput = document.getElementById('planned_move_in_date');
    const durationSelect = document.getElementById('rental_duration');
    const endDateInput = document.getElementById('planned_end_date');

    function calculateEndDate() {
        const moveInDate = moveInDateInput.value;
        const duration = parseInt(durationSelect.value);

        if (moveInDate && duration) {
            const startDate = new Date(moveInDate);
            const endDate = new Date(startDate);

            // Add months to the start date
            endDate.setMonth(endDate.getMonth() + duration);

            // Subtract one day to get the last day of the rental period
            endDate.setDate(endDate.getDate() - 1);

            // Format date as YYYY-MM-DD
            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');

            endDateInput.value = `${year}-${month}-${day}`;
        } else {
            endDateInput.value = '';
        }
    }

    // Add event listeners
    moveInDateInput.addEventListener('change', calculateEndDate);
    durationSelect.addEventListener('change', calculateEndDate);

    // Calculate on page load if values are already set
    calculateEndDate();
});
</script>
@endsection
