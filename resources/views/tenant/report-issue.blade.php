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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Report Maintenance Issue</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Submit a maintenance request for your rental property</p>
            </div>

            @if($rentalListing)
            <!-- Property Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Property Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Property Name</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $rentalListing->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                        <p class="text-gray-900 dark:text-white">{{ $rentalListing->location }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Landlord</p>
                        <p class="text-gray-900 dark:text-white">{{ $rentalListing->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Contact</p>
                        <p class="text-gray-900 dark:text-white">{{ $rentalListing->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Report Issue Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Issue Details</h2>

                <form method="POST" action="{{ route('tenant.report-issue') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Issue Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issue Category *</label>
                        <select id="category" name="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Select a category</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="electrical">Electrical</option>
                            <option value="heating_cooling">Heating/Cooling</option>
                            <option value="appliances">Appliances</option>
                            <option value="structural">Structural</option>
                            <option value="pest_control">Pest Control</option>
                            <option value="cleaning">Cleaning/Maintenance</option>
                            <option value="security">Security</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Issue Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issue Title *</label>
                        <input type="text" id="title" name="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Brief description of the issue" required>
                    </div>

                    <!-- Issue Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detailed Description *</label>
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Please provide detailed information about the issue, including when it started, what you've observed, and any steps you've already taken." required></textarea>
                    </div>

                    <!-- Urgency Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Urgency Level *</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="low" name="urgency" value="low" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="low" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                        Low - Can wait a few days
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="medium" name="urgency" value="medium" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <label for="medium" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                        Medium - Should be addressed soon
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="high" name="urgency" value="high" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="high" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                                        High - Needs immediate attention
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="emergency" name="urgency" value="emergency" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="emergency" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        Emergency - Critical issue affecting safety/health
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Location in Property -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location in Property</label>
                        <input type="text" id="location" name="location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="e.g., Kitchen, Bedroom 1, Bathroom, Living Room, etc.">
                    </div>

                    <!-- Preferred Contact Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Preferred Contact Method</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="email" name="contact_method" value="email" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <label for="email" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="phone" name="contact_method" value="phone" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="phone" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="text" name="contact_method" value="text" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="text" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Text Message</label>
                            </div>
                        </div>
                    </div>

                    <!-- Available Times for Access -->
                    <div>
                        <label for="available_times" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Times for Property Access</label>
                        <textarea id="available_times" name="available_times" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Please specify your availability for maintenance personnel to access the property (e.g., weekdays 9am-5pm, weekends anytime)"></textarea>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photos (Optional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="photos" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload photos</span>
                                        <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 10MB each</p>
                            </div>
                        </div>
                        <div id="photo-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700 dark:text-gray-300">
                                I understand that this is a request for maintenance and not a guarantee of immediate service. Response times may vary based on urgency and availability.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tenant.rental') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Submit Issue Report
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Issues -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Maintenance Requests</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Issue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Urgency</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Sample issues - in real app this would come from database -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ now()->subDays(3)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Leaky faucet in kitchen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Plumbing</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                        Medium
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                        In Progress
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <!-- No Active Rental Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Active Rental</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">You need an active rental to report maintenance issues.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photos');
    const photoPreview = document.getElementById('photo-preview');

    photoInput.addEventListener('change', function(e) {
        photoPreview.innerHTML = '';
        const files = Array.from(e.target.files);

        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs" onclick="removePhoto(${index})">
                            ×
                        </button>
                    `;
                    photoPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});

function removePhoto(index) {
    const photoInput = document.getElementById('photos');
    const files = Array.from(photoInput.files);
    files.splice(index, 1);

    // Create new FileList
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    photoInput.files = dt.files;

    // Trigger change event to update preview
    photoInput.dispatchEvent(new Event('change'));
}
</script>
@endsection
