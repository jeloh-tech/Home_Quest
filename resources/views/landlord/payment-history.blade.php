@extends('layouts.landlord')

@section('content')
<div class="w-full -m-10">
    <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Payment History</h1>
                            <p class="text-sm text-gray-600 mt-1">View all rent payments received from your tenants</p>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Total Received</p>
                                    <p class="text-2xl font-bold">₱{{ number_format($totalReceived ?? 0, 2) }}</p>
                                </div>
                                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">This Month</p>
                                    <p class="text-2xl font-bold">₱{{ number_format($thisMonthReceived ?? 0, 2) }}</p>
                                </div>
                                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                                    <p class="text-2xl font-bold">₱{{ number_format($pendingAmount ?? 0, 2) }}</p>
                                </div>
                                <svg class="w-8 h-8 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Active Tenants</p>
                                    <p class="text-2xl font-bold">{{ $activeTenants ?? 0 }}</p>
                                </div>
                                <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('landlord.payment-history') }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Tenant/Property">
                            </div>
                            <div>
                                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_filter" id="status_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="completed" {{ request('status_filter') == 'completed' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div>
                                <label for="month_filter" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                                <select name="month_filter" id="month_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('month_filter') == 'all' ? 'selected' : '' }}>All Months</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month_filter') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="year_filter" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                <select name="year_filter" id="year_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('year_filter') == 'all' ? 'selected' : '' }}>All Years</option>
                                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                        <option value="{{ $year }}" {{ request('year_filter') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'status_filter', 'month_filter', 'year_filter']))
                                <a href="{{ route('landlord.payment-history') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-500">
                                    Clear filters
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $payment->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($payment->tenant && $payment->tenant->profile_photo_path)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $payment->tenant->profile_photo_path) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">{{ substr($payment->tenant->name ?? 'T', 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $payment->tenant->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $payment->tenant->email ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $payment->listing->title ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->listing->location ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱{{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @switch($payment->payment_method)
                                                    @case('credit_card')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('bank_transfer')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('gcash')
                                                        bg-purple-100 text-purple-800
                                                        @break
                                                    @case('maya')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('qr_code')
                                                        bg-orange-100 text-orange-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @switch($payment->status)
                                                    @case('completed')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('pending')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('failed')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch">
                                                @switch($payment->status)
                                                    @case('completed')
                                                        Paid
                                                        @break
                                                    @case('pending')
                                                        Pending
                                                        @break
                                                    @case('failed')
                                                        Failed
                                                        @break
                                                    @default
                                                        {{ ucfirst($payment->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                            <div class="text-xs text-gray-400">{{ $payment->payment_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="showPaymentDetails({{ $payment->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">View Details</button>
                                            @if($payment->status === 'pending')
                                                <button onclick="markAsReceived({{ $payment->id }})"
                                                    class="text-green-600 hover:text-green-900">Mark Received</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Details Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Payment Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function formatTransactionDetails(details) {
    try {
        const parsed = JSON.parse(details);
        return '<div><h4 class="font-semibold text-gray-900">Transaction Details</h4><pre class="bg-gray-100 p-2 rounded text-sm">' + JSON.stringify(parsed, null, 2) + '</pre></div>';
    } catch (e) {
        return '<div><h4 class="font-semibold text-gray-900">Transaction Details</h4><pre class="bg-gray-100 p-2 rounded text-sm">' + details + '</pre></div>';
    }
}

function showPaymentDetails(paymentId) {
    fetch(`/landlord/payments/${paymentId}/details`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const payment = data.payment;
            let statusClass = 'bg-yellow-100 text-yellow-800';
            if (payment.status === 'completed') {
                statusClass = 'bg-green-100 text-green-800';
            } else if (payment.status === 'failed') {
                statusClass = 'bg-red-100 text-red-800';
            }

            let content = `<div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Payment Information</h4>
                        <p><strong>ID:</strong> #${payment.id}</p>
                        <p><strong>Amount:</strong> ₱${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                        <p><strong>Method:</strong> ${payment.payment_method.replace('_', ' ').toUpperCase()}</p>
                        <p><strong>Date:</strong> ${payment.payment_date}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}</span></p>
                        <p><strong>On Time:</strong> ${payment.is_on_time ? 'Yes' : 'No'}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Tenant Information</h4>
                        <p><strong>Name:</strong> ${payment.tenant.name}</p>
                        <p><strong>Email:</strong> ${payment.tenant.email}</p>
                        <p><strong>Phone:</strong> ${payment.tenant.phone || 'N/A'}</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Property Information</h4>
                    <p><strong>Title:</strong> ${payment.listing.title}</p>
                    <p><strong>Location:</strong> ${payment.listing.location}</p>
                </div>
            </div>`;

            if (payment.verified_at) {
                content += `<div><h4 class="font-semibold text-gray-900">Verified At</h4><p>${payment.verified_at} by ${payment.verifier || 'System'}</p></div>`;
            }
            if (payment.rejected_at) {
                content += `<div><h4 class="font-semibold text-gray-900">Rejected At</h4><p>${payment.rejected_at} by ${payment.rejecter || 'System'}</p></div>`;
            }
            if (payment.rejection_reason) {
                content += `<div><h4 class="font-semibold text-gray-900">Rejection Reason</h4><p class="text-red-600">${payment.rejection_reason}</p></div>`;
            }
            if (payment.notes) {
                content += `<div><h4 class="font-semibold text-gray-900">Notes</h4><p>${payment.notes}</p></div>`;
            }
            if (payment.receipt_url) {
                content += `<div><h4 class="font-semibold text-gray-900">Receipt</h4><a href="${payment.receipt_url}" target="_blank" class="text-blue-600 hover:text-blue-800">View Receipt</a></div>`;
            }
            let transactionHtml = '';
            if (payment.transaction_details) {
                try {
                    const details = JSON.parse(payment.transaction_details);
                    transactionHtml = `<div><h4 class="font-semibold text-gray-900">Transaction Details</h4><pre class="bg-gray-100 p-2 rounded text-sm">${JSON.stringify(details, null, 2)}</pre></div>`;
                } catch (e) {
                    transactionHtml = `<div><h4 class="font-semibold text-gray-900">Transaction Details</h4><pre class="bg-gray-100 p-2 rounded text-sm">${payment.transaction_details}</pre></div>`;
                }
            }
            content += transactionHtml;
            if (payment.status === 'pending') {
                content += `<div class="flex space-x-2 pt-4"><button onclick="markAsReceived(${payment.id})" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Mark as Received</button><button onclick="rejectPayment(${payment.id})" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject Payment</button></div>`;
            }

            content += '</div>';

            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('paymentModal').classList.remove('hidden');
        } else {
            alert('Failed to load payment details.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading payment details.');
    });
}

function markAsReceived(paymentId) {
    if (confirm('Are you sure you want to mark this payment as received?')) {
        fetch(`/landlord/payments/${paymentId}/verify`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment marked as received successfully.');
                closeModal();
                location.reload(); // Reload to update the table
            } else {
                alert('Failed to mark payment as received: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking the payment as received.');
        });
    }
}

function rejectPayment(paymentId) {
    const reason = prompt('Please enter the reason for rejection:');
    if (reason && reason.trim()) {
        fetch(`/landlord/payments/${paymentId}/reject`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ reason: reason.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment rejected successfully.');
                closeModal();
                location.reload(); // Reload to update the table
            } else {
                alert('Failed to reject payment: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rejecting the payment.');
        });
    }
}

function closeModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
