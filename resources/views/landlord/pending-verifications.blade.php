@extends('layouts.landlord')

@section('content')
<div class="w-full -m-10">
    <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('landlord.pending-verifications') }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Tenant/Property">
                            </div>
                            <div>
                                <label for="method_filter" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                <select name="method_filter" id="method_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('method_filter') == 'all' ? 'selected' : '' }}>All Methods</option>
                                    <option value="gcash" {{ request('method_filter') == 'gcash' ? 'selected' : '' }}>GCash</option>
                                    <option value="maya" {{ request('method_filter') == 'maya' ? 'selected' : '' }}>Maya</option>
                                    <option value="bank_transfer" {{ request('method_filter') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="manual" {{ request('method_filter') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'method_filter', 'date_from', 'date_to']))
                                <a href="{{ route('landlord.pending-verifications') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-500">
                                    Clear filters
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Pending Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pendingPayments as $payment)
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
                                                    @case('gcash')
                                                        bg-purple-100 text-purple-800
                                                        @break
                                                    @case('maya')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('bank_transfer')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('manual')
                                                        bg-orange-100 text-orange-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                            <div class="text-xs text-gray-400">{{ $payment->payment_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="showPaymentDetails({{ $payment->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">View Details</button>
                                            <button onclick="verifyPayment({{ $payment->id }})"
                                                class="text-green-600 hover:text-green-900 mr-3">Verify</button>
                                            <button onclick="rejectPayment({{ $payment->id }})"
                                                class="text-red-600 hover:text-red-900">Reject</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No pending payments to verify.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pendingPayments->links() }}
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
            } else if (payment.status === 'rejected') {
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
                content += `<div class="flex space-x-2 pt-4"><button onclick="verifyPayment(${payment.id})" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Verify Payment</button><button onclick="rejectPayment(${payment.id})" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject Payment</button></div>`;
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

function verifyPayment(paymentId) {
    if (confirm('Are you sure you want to verify this payment as received?')) {
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
                alert('Payment verified successfully.');
                closeModal();
                location.reload(); // Reload to update the table
            } else {
                alert('Failed to verify payment: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while verifying the payment.');
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
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rejection_reason: reason.trim() })
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
