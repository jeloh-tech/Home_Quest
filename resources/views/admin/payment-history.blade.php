@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Include the separate sidebar -->
    @include('admin.sidebar')

    <div class="flex-1 min-h-screen bg-gray-50 ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                <h1 class="text-2xl font-bold text-gray-900">Payment History</h1>
                <p class="text-sm text-gray-600 mt-1">Admin Features: View all payment transactions</p>
            </div>
        </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.payment-history') }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Tenant/Property">
                            </div>
                            <div>
                                <label for="landlord_filter" class="block text-sm font-medium text-gray-700 mb-1">Landlord</label>
                                <select name="landlord_filter" id="landlord_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('landlord_filter') == 'all' ? 'selected' : '' }}>All Landlords</option>
                                    @foreach(\App\Models\User::where('role', 'landlord')->orderBy('name')->get() as $landlord)
                                        <option value="{{ $landlord->id }}" {{ request('landlord_filter') == $landlord->id ? 'selected' : '' }}>
                                            {{ $landlord->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_filter" id="status_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="completed" {{ request('status_filter') == 'completed' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="overdue" {{ request('status_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div>
                                <label for="method_filter" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                <select name="method_filter" id="method_filter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all" {{ request('method_filter') == 'all' ? 'selected' : '' }}>All Methods</option>
                                    <option value="credit_card" {{ request('method_filter') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="bank_transfer" {{ request('method_filter') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="gcash" {{ request('method_filter') == 'gcash' ? 'selected' : '' }}>GCash</option>
                                    <option value="maya" {{ request('method_filter') == 'maya' ? 'selected' : '' }}>Maya</option>
                                    <option value="qr_code" {{ request('method_filter') == 'qr_code' ? 'selected' : '' }}>QR Code</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Filter
                                </button>
                            </div>
                        </div>
                        @if(request()->hasAny(['search', 'landlord_filter', 'status_filter', 'method_filter']))
                            <div class="mt-2 flex justify-between items-center">
                                <a href="{{ route('admin.payment-history') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-500">
                                    Clear filters
                                </a>
                                <a href="{{ route('admin.export-payments', request()->query()) }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Export to CSV
                                </a>
                            </div>
                        @else
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('admin.export-payments') }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Export to CSV
                                </a>
                            </div>
                        @endif
                    </form>

                    <!-- Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Landlord</th>
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
                                                            <span class="text-sm font-medium text-gray-700">{{ substr($payment->tenant->name ?? 'N', 0, 1) }}</span>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    @if($payment->listing->user && $payment->listing->user->profile_photo_path)
                                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $payment->listing->user->profile_photo_path) }}" alt="">
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700">{{ substr($payment->listing->user->name ?? 'L', 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $payment->listing->user->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $payment->listing->user->email ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @if($payment->status == 'completed')
                                                <button onclick="handleRefund({{ $payment->id }})"
                                                    class="text-red-600 hover:text-red-900">Refund</button>
                                            @endif
                                            @if($payment->status == 'completed')
                                                <button onclick="handleDispute({{ $payment->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900">Dispute</button>
                                            @endif
                                            <button onclick="handleRemove({{ $payment->id }})"
                                                class="text-red-600 hover:text-red-900">Remove</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
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
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" style="display: none;">
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
function showPaymentDetails(paymentId) {
    fetch(`/admin/payments/${paymentId}/details`, {
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
            switch (payment.status) {
                case 'completed':
                    statusClass = 'bg-green-100 text-green-800';
                    break;
                case 'failed':
                    statusClass = 'bg-red-100 text-red-800';
                    break;
                case 'refunded':
                    statusClass = 'bg-orange-100 text-orange-800';
                    break;
                case 'disputed':
                    statusClass = 'bg-purple-100 text-purple-800';
                    break;
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
                        <p><strong>Name:</strong> ${payment.tenant ? payment.tenant.name : 'N/A'}</p>
                        <p><strong>Email:</strong> ${payment.tenant ? payment.tenant.email : 'N/A'}</p>
                        <p><strong>Phone:</strong> ${payment.tenant && payment.tenant.phone ? payment.tenant.phone : 'N/A'}</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Property Information</h4>
                    <p><strong>Title:</strong> ${payment.listing ? payment.listing.title : 'N/A'}</p>
                    <p><strong>Location:</strong> ${payment.listing ? payment.listing.location : 'N/A'}</p>
                    <p><strong>Landlord:</strong> ${payment.listing && payment.listing.user ? payment.listing.user.name + ' (' + payment.listing.user.email + ')' : 'N/A'}</p>
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
            if (payment.status === 'completed') {
                content += `<div class="flex space-x-2 pt-4"><button onclick="handleRefund(${payment.id})" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Process Refund</button><button onclick="handleDispute(${payment.id})" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Mark as Disputed</button></div>`;
            }

            content += '</div>';

            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Payment Details';
        } else {
            alert('Failed to load payment details.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading payment details.');
    });
}

function closeModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
function handleRefund(paymentId) {
    const reason = prompt('Please provide a reason for refunding this payment:');
    if (reason && reason.trim() !== '') {
        fetch(`/admin/payments/${paymentId}/refund`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: reason.trim()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Refund processed successfully.');
                location.reload();
            } else {
                alert('Failed to process refund: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the refund.');
        });
    } else if (reason !== null) {
        alert('Reason is required to refund a payment.');
    }
}

function handleDispute(paymentId) {
    const reason = prompt('Please provide a reason for disputing this payment:');
    if (reason && reason.trim() !== '') {
        fetch('/admin/payments/' + paymentId + '/dispute', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: reason.trim()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment marked as disputed successfully.');
                location.reload();
            } else {
                alert('Failed to mark payment as disputed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking the payment as disputed.');
        });
    } else if (reason !== null) {
        alert('Reason is required to dispute a payment.');
    }
}

function handleRemove(paymentId) {
    if (confirm('Are you sure you want to remove this payment? This action cannot be undone.')) {
        fetch('/admin/payments/' + paymentId + '/remove', {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment removed successfully.');
                location.reload();
            } else {
                alert('Failed to remove payment: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the payment.');
        });
    }
}
</script>
@endsection
