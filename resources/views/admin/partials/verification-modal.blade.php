<!-- Verification Modal -->
<div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Review Landlord Application</h3>
            <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Loading State -->
            <div id="modalLoading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading verification details...</p>
            </div>

            <!-- Content Container -->
            <div id="modalContent" class="hidden">
                <!-- User Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Personal Information</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Name:</span> <span id="userName">Loading...</span></p>
                            <p><span class="font-medium">Email:</span> <span id="userEmail">Loading...</span></p>
                            <p><span class="font-medium">Phone:</span> <span id="userPhone">Loading...</span></p>
                            <p><span class="font-medium">Role:</span> <span id="userRole" class="capitalize">Loading...</span></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Verification Details</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Status:</span> <span id="verificationStatus" class="px-2 py-1 rounded text-sm font-medium">Loading...</span></p>
                            <p><span class="font-medium">Document Type:</span> <span id="documentType">Loading...</span></p>
                            <p><span class="font-medium">Submitted:</span> <span id="createdAt">Loading...</span></p>
                            <p><span class="font-medium">Notes:</span> <span id="verificationNotes">None</span></p>
                        </div>
                    </div>
                </div>

                <!-- Document Images -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Verification Documents</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Front ID -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-md font-medium text-gray-800 mb-3">ID Front</h5>
                            <div id="frontImageContainer" class="text-center">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                <p class="mt-2 text-gray-600 text-sm">Loading image...</p>
                            </div>
                        </div>

                        <!-- Back ID -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-md font-medium text-gray-800 mb-3">ID Back</h5>
                            <div id="backImageContainer" class="text-center">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                <p class="mt-2 text-gray-600 text-sm">Loading image...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejection Notes Input (Hidden by default) -->
                <div id="rejectionNotesSection" class="mb-6 hidden">
                    <label for="rejectionNotes" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="rejectionNotes"
                        name="rejectionNotes"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Please provide a reason for rejection..."
                        maxlength="500"
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">Maximum 500 characters</p>
                </div>
            </div>

            <!-- Error Message -->
            <div id="modalError" class="hidden bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800" id="errorMessage">An error occurred while loading the verification details.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end px-6 py-4 border-t space-x-3">
            <button onclick="closeVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Cancel
            </button>

            <!-- Approve Button -->
            <button id="approveBtn" onclick="approveVerification()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Approve
            </button>

            <!-- Reject Button -->
            <button id="rejectBtn" onclick="showRejectionNotes()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reject
            </button>

            <!-- Confirm Reject Button (Hidden by default) -->
            <button id="confirmRejectBtn" onclick="rejectVerification()" class="hidden px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Confirm Rejection
            </button>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div id="toast" class="fixed bottom-4 right-4 z-50 hidden">
    <div class="bg-white border rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0">
                <!-- Icon will be set by JavaScript -->
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
