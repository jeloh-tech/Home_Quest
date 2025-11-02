 @extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <ul class="text-sm font-medium text-red-800 dark:text-red-200">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Pay Rent</h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">Secure and easy rent payment</p>
                    </div>
                </div>
                <!-- Progress Steps -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                        <span class="ml-2 text-sm font-medium text-green-600 dark:text-green-400">Property Details</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                        <span class="ml-2 text-sm font-medium text-blue-600 dark:text-blue-400">Payment Method</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold text-sm">3</div>
                        <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Confirmation</span>
                    </div>
                </div>
            </div>

            @if($rentalListing)
            <!-- Payment Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Property Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Property Details</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Property</p>
                                <p class="text-gray-900 dark:text-white">{{ $rentalListing->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                                <p class="text-gray-900 dark:text-white">{{ $rentalListing->location }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Rent</p>
                                <p class="text-lg font-semibold text-green-600 dark:text-green-400">₱{{ number_format($rentalListing->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Payment Details</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date</p>
                                <p class="text-gray-900 dark:text-white">{{ $dueDate }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Amount Due</p>
                                <p class="text-lg font-semibold text-green-600 dark:text-green-400">₱{{ number_format($rentalListing->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                                <span class="px-2 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                    On Time
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Make Payment</h2>

                <form id="paymentForm" method="POST" action="{{ route('tenant.pay-rent') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Payment Method</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" id="gcash" name="payment_method" value="gcash" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <label for="gcash" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        GCash
                                    </div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="maya" name="payment_method" value="maya" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="maya" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Maya
                                    </div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Bank Transfer
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>



                    <!-- GCash Details (shown when GCash is selected) -->
                    <div id="gcash_details" class="hidden space-y-4">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-green-800 dark:text-green-200 mb-2">GCash Payment Instructions</h4>
                            <p class="text-sm text-green-700 dark:text-green-300 mb-3">Send the rent payment to the landlord's GCash account:</p>
                            <div class="space-y-2 text-sm">
                                <p><strong>GCash Number:</strong> {{ $rentalListing->user->gcash_number ?? '09123456789' }}</p>
                                <p><strong>Account Name:</strong> {{ $rentalListing->user->name }}</p>
                                <p><strong>Amount:</strong> ₱{{ number_format($rentalListing->price, 2) }}</p>
                                <p><strong>Message:</strong> Rent Payment - {{ $rentalListing->title }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="gcash_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your GCash Number</label>
                            <input type="text" id="gcash_number" name="gcash_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="09XX XXX XXXX">
                        </div>

                        <div>
                            <label for="gcash_reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number (Optional)</label>
                            <input type="text" id="gcash_reference_number" name="gcash_reference_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter GCash reference number">
                        </div>
                    </div>

                    <!-- Maya Details (shown when Maya is selected) -->
                    <div id="maya_details" class="hidden space-y-4">
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200 mb-2">Maya Payment Instructions</h4>
                            <p class="text-sm text-purple-700 dark:text-purple-300 mb-3">Send the rent payment to the landlord's Maya account:</p>
                            <div class="space-y-2 text-sm">
                                <p><strong>Maya Number:</strong> {{ $rentalListing->user->maya_number ?? '09123456789' }}</p>
                                <p><strong>Account Name:</strong> {{ $rentalListing->user->name }}</p>
                                <p><strong>Amount:</strong> ₱{{ number_format($rentalListing->price, 2) }}</p>
                                <p><strong>Message:</strong> Rent Payment - {{ $rentalListing->title }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="maya_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Maya Number</label>
                            <input type="text" id="maya_number" name="maya_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="09XX XXX XXXX">
                        </div>

                        <div>
                            <label for="maya_reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number (Optional)</label>
                            <input type="text" id="maya_reference_number" name="maya_reference_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter Maya reference number">
                        </div>
                    </div>

                    <!-- Bank Transfer Details (shown when Bank Transfer is selected) -->
                    <div id="bank_transfer_details" class="hidden space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Bank Transfer Payment Instructions</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">Transfer the rent payment to the landlord's bank account:</p>
                            <div class="space-y-2 text-sm">
                                <p><strong>Bank Name:</strong> {{ $rentalListing->user->bank_name ?? 'BDO' }}</p>
                                <p><strong>Account Number:</strong> {{ $rentalListing->user->bank_account_number ?? '1234567890' }}</p>
                                <p><strong>Account Name:</strong> {{ $rentalListing->user->name }}</p>
                                <p><strong>Amount:</strong> ₱{{ number_format($rentalListing->price, 2) }}</p>
                                <p><strong>Message:</strong> Rent Payment - {{ $rentalListing->title }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="bank_reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number (Optional)</label>
                            <input type="text" id="bank_reference_number" name="bank_reference_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter bank reference number">
                        </div>
                    </div>



                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" id="amount" name="amount" step="0.01" class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">PHP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Any additional notes for this payment"></textarea>
                    </div>

                    <!-- Receipt Upload -->
                    <div>
                        <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Receipt (Optional)</label>
                        <input type="file" id="receipt" name="receipt" accept="image/*,.pdf" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload an image or PDF of your payment receipt</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('tenant.rental') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Send
                        </button>
                    </div>
                </form>
            </div>
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 transform transition-all duration-300 hover:shadow-2xl">

            <!-- Payment History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Payments</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No recent payments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <!-- No Active Rental Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Active Rental</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">You don't have any active rentals to make payments for.</p>
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
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const gcashDetails = document.getElementById('gcash_details');
    const mayaDetails = document.getElementById('maya_details');
    const bankTransferDetails = document.getElementById('bank_transfer_details');
    const submitBtn = document.getElementById('submitBtn');

    function togglePaymentMethod() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

        // Hide all details first
        gcashDetails.classList.add('hidden');
        mayaDetails.classList.add('hidden');
        bankTransferDetails.classList.add('hidden');

        // Show selected method details
        if (selectedMethod === 'gcash') {
            gcashDetails.classList.remove('hidden');
        } else if (selectedMethod === 'maya') {
            mayaDetails.classList.remove('hidden');
        } else if (selectedMethod === 'bank_transfer') {
            bankTransferDetails.classList.remove('hidden');
        }
    }

    // Add event listeners
    radios.forEach(radio => {
        radio.addEventListener('change', togglePaymentMethod);
    });



    // Initialize on page load
    togglePaymentMethod();
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

/* Custom radio button styles */
input[type="radio"]:checked + label > div > div:last-child > div {
    background-color: currentColor;
}

/* Hover effects for payment method cards */
.payment-method-card label:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Loading spinner animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Gradient text effects */
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
</style>
@endsection
