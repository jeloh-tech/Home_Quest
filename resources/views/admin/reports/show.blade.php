@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <!-- Main Content Area -->
    <main class="ml-64 flex-1 p-8 overflow-y-auto">
        @include('components.login-success-modal')
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('admin.reports.index') }}"
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Reports
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Report Details</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Report ID: #{{ $report->id }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($report->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($report->status === 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($report->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                            @endif">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Report Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Report Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Reason</label>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($report->reason === 'inappropriate_content') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($report->reason === 'spam') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($report->reason === 'fraudulent') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                    @elseif($report->reason === 'offensive') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
                                    @elseif($report->reason === 'duplicate') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($report->reason === 'wrong_information') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $report->reason)) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported Date</label>
                                <p class="text-gray-900 dark:text-white">{{ $report->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($report->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Details</label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->description }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Reporter Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Reporter Information</h2>

                        @if($report->user)
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-gray-700 dark:text-gray-300">
                                    {{ substr($report->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $report->user->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ $report->user->email }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Role: {{ ucfirst($report->user->role) }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Anonymous User</h3>
                                <p class="text-gray-600 dark:text-gray-400 italic">No contact information provided</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">This report was submitted anonymously</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Reported Property -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Reported Property</h2>

                        @if($report->listing)
                        <div class="flex items-start space-x-4">
                            @if($report->listing->images && count($report->listing->images) > 0)
                            <div class="w-24 h-24 flex-shrink-0">
                                <img src="{{ asset('storage/' . $report->listing->images[0]) }}"
                                     alt="Property image"
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $report->listing->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $report->listing->location }}</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($report->listing->price, 2) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">per month</p>
                                <div class="mt-4">
                                    <a href="{{ route('admin.listings.show', $report->listing) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Property Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-600 dark:text-gray-400">Property information not available.</p>
                        @endif
                    </div>

                    <!-- Admin Notes History -->
                    @if($report->admin_notes || $report->reviewed_at)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Review History</h2>

                        @if($report->reviewed_at)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Last reviewed on {{ $report->reviewed_at->format('F d, Y \a\t g:i A') }}
                                @if($report->reviewer)
                                    by {{ $report->reviewer->name }}
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($report->admin_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admin Notes</label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->admin_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Update Report Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Update Report Status</h2>

                        <form id="updateReportForm" onsubmit="updateReportStatus(event)">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Status Selection -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>

                                <!-- Admin Notes -->
                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Admin Notes <span class="text-gray-500">(optional)</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="4"
                                              placeholder="Add notes about this report..."
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors resize-vertical">{{ $report->admin_notes }}</textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" id="updateBtn"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Report
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h2>

                        <div class="space-y-4">
                            @if($report->listing)
                            <a href="{{ route('admin.listings.show', $report->listing) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center block flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Property
                            </a>
                            @endif

                            @if($report->user)
                            <a href="{{ route('admin.tenants.show', $report->user) }}"
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center block flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Reporter
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function updateReportStatus(event) {
    event.preventDefault();

    const form = document.getElementById('updateReportForm');
    const formData = new FormData(form);
    const updateBtn = document.getElementById('updateBtn');
    const originalText = updateBtn.innerHTML;

    // Disable button and show loading
    updateBtn.disabled = true;
    updateBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Updating...
    `;

    fetch('{{ route("admin.reports.update", $report) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message, 'success');

            // Update status badge
            const statusBadge = document.querySelector('.px-3.py-1.rounded-full');
            const newStatus = formData.get('status');
            statusBadge.className = `px-3 py-1 rounded-full text-sm font-medium ${
                newStatus === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                newStatus === 'reviewed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
            }`;
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

            // Reload page after short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error message
            showNotification(data.message || 'An error occurred while updating the report.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the report. Please try again.', 'error');
    })
    .finally(() => {
        // Re-enable button
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500', 'text-white');
    } else {
        notification.classList.add('bg-blue-500', 'text-white');
    }

    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 300);
    }, 5000);
}
</script>
@endsection
