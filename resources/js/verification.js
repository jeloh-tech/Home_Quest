// Enhanced verification modal functionality with improved error handling and UX

// Global variables to track current user and modal state
let currentUserId = null;
let currentUserType = null;
let isModalLoading = false;
let retryCount = 0;
const MAX_RETRIES = 3;
let isLandlordView = false; // Flag to determine if viewing own verification data

// Initialize verification modal functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced verification modal script loaded successfully');

    // Ensure all functions are globally available
    window.openVerificationModal = openVerificationModal;
    window.closeVerificationModal = closeVerificationModal;
    window.openLandlordVerificationModal = openLandlordVerificationModal;
    window.approveVerification = approveVerification;
    window.rejectVerification = rejectVerification;
    window.openDocumentFullscreen = openDocumentFullscreen;
    window.closeDocumentFullscreen = closeDocumentFullscreen;
    window.retryLoadVerification = retryLoadVerification;
    window.showNotification = showNotification;
    window.populateLandlordVerificationData = populateLandlordVerificationData;
    window.proceedToVerification = proceedToVerification;

    console.log('All verification modal functions are now available globally');
});

// Function to redirect to verification page
function proceedToVerification() {
    window.location.href = '/landlord/verify';
}

// Helper function to format document type (same as blade template)
function formatDocumentType(documentType) {
    if (!documentType || documentType === 'Not specified') {
        return 'Not specified';
    }

    // Map document types to proper display names
    const docTypeLabels = {
        'philippine_id': 'Philippine ID (PhilID)',
        'drivers_license': 'Driver\'s License',
        'sss_gsis': 'SSS/GSIS ID',
        'passport': 'Passport',
        'birth_certificate': 'Birth Certificate',
        'other': 'Other Valid ID'
    };

    return docTypeLabels[documentType] || documentType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

// Simple notification function to show messages to user
function showNotification(message, type = 'info') {
    // Create notification container if not exists
    let container = document.getElementById('notificationContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificationContainer';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '100000';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '10px';
        document.body.appendChild(container);
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.padding = '10px 20px';
    notification.style.borderRadius = '8px';
    notification.style.color = '#fff';
    notification.style.minWidth = '200px';
    notification.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
    notification.style.fontWeight = '600';
    notification.style.fontFamily = 'Arial, sans-serif';
    notification.style.opacity = '0';
    notification.style.transition = 'opacity 0.3s ease';

    // Set background color based on type
    switch(type) {
        case 'success':
            notification.style.backgroundColor = '#22c55e'; // green
            break;
        case 'error':
            notification.style.backgroundColor = '#ef4444'; // red
            break;
        case 'warning':
            notification.style.backgroundColor = '#facc15'; // yellow
            notification.style.color = '#000';
            break;
        default:
            notification.style.backgroundColor = '#3b82f6'; // blue
    }

    container.appendChild(notification);

    // Fade in
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
    });

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.addEventListener('transitionend', () => {
            notification.remove();
            if (container.children.length === 0) {
                container.remove();
            }
        });
    }, 3000);
}

// Create and inject modal HTML
async function createVerificationModal() {
    return new Promise((resolve) => {
        const modalHTML = `
            <!-- Blur Background Layer -->
            <div id="verificationModalBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-20 backdrop-blur-sm z-40"></div>

            <div id="verificationModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-4xl shadow-2xl rounded-2xl bg-white">
                    <div class="mt-3">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <h3 class="text-2xl font-extrabold text-gray-900 tracking-wide">Review Landlord Application</h3>
                            <button id="closeVerificationModalBtn" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-2 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div id="loadingState" class="flex flex-col items-center justify-center py-20 space-y-6">
                            <div class="relative">
                                <div class="rounded-full h-16 w-16 border-4 border-b-4 border-blue-600"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-blue-200 opacity-20"></div>
                            </div>
                            <div class="text-center space-y-2">
                                <span class="text-xl font-semibold text-gray-700">Loading verification details...</span>
                                <p class="text-sm text-gray-500">Please wait while we fetch the user's information</p>
                            </div>
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Content State -->
                        <div id="verificationContent" class="hidden p-6 space-y-6">
                            <!-- User Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-5 bg-gray-50 p-5 rounded-xl shadow-inner border border-gray-200">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">User ID</label>
                                        <p id="userId" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">User Type</label>
                                        <p id="userType" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Name</label>
                                        <p id="userName" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Email</label>
                                        <p id="userEmail" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                </div>
                                <div class="space-y-5 bg-gray-50 p-5 rounded-xl shadow-inner border border-gray-200">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Phone</label>
                                        <p id="userPhone" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Created At</label>
                                        <p id="userCreatedAt" class="mt-1 text-base text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Document Type</label>
                                        <p id="documentType" class="mt-1 text-base text-gray-900">Not specified</p>
                                    </div>
                                </div>
                            </div>



                            <!-- Current Document Preview -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Current Verification Document</label>
                                <div id="documentPreview" class="border border-gray-300 rounded-lg p-5 bg-gray-50 shadow-sm">
                                    <!-- Document content will be populated here -->
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <button onclick="closeVerificationModal()" class="hidden flex items-center px-5 py-3 bg-gray-500 text-white rounded-2xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 transition-transform transform hover:scale-105 shadow-lg">
                                <svg class="h-6 w-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                                <div class="flex space-x-4">
                                    <button id="rejectBtn" onclick="rejectVerification()" class="flex items-center px-5 py-3 bg-red-600 text-white rounded-2xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 transition-transform transform hover:scale-105 shadow-lg">
                                        <svg class="h-6 w-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                    <button id="approveBtn" onclick="approveVerification()" class="flex items-center px-5 py-3 bg-green-600 text-white rounded-2xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 transition-transform transform hover:scale-105 shadow-lg">
                                        <svg class="h-6 w-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Approve
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Error State -->
                        <div id="errorState" class="hidden p-6 text-center">
                            <div class="text-red-600 mb-6">
                                <svg class="h-14 w-14 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p id="errorMessage" class="text-xl font-semibold">An error occurred</p>
                            </div>
                            <button onclick="retryLoadVerification()" class="px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if it exists
        const existingModal = document.getElementById('verificationModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Inject modal into body
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Add event listener for close button
        const closeBtn = document.getElementById('closeVerificationModalBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeVerificationModal();
            });
        }

        // Create fullscreen modal separately and append to body
        createFullscreenModal();

        // Use setTimeout to ensure DOM is updated
        setTimeout(() => {
            resolve();
        }, 0);
    });
}

// Create fullscreen modal separately
function createFullscreenModal() {
    const fullscreenHTML = `
        <!-- Fullscreen Document Modal -->
        <div id="documentFullscreenModal" class="fixed inset-0 bg-gray-900 bg-opacity-90 flex items-center justify-center hidden overflow-auto" style="z-index: 99999 !important;">
            <div class="relative max-w-4xl w-full p-6 flex items-center justify-center min-h-full">
                <button onclick="closeDocumentFullscreen()" aria-label="Close fullscreen document" class="absolute top-12 right-4 text-white hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-lg px-4 py-3 transition-colors bg-red-600 hover:bg-red-700 shadow-2xl border-2 border-red-500" style="z-index: 100000 !important;">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-sm font-semibold">CLOSE</span>
                    </div>
                </button>
                <img id="fullscreenDocumentImage" src="" alt="Document" class="max-w-full w-auto h-auto object-contain rounded-lg shadow-lg">
            </div>
        </div>
    `;

    // Remove existing fullscreen modal if it exists
    const existingFullscreen = document.getElementById('documentFullscreenModal');
    if (existingFullscreen) {
        existingFullscreen.remove();
    }

    // Append fullscreen modal directly to body
    document.body.insertAdjacentHTML('beforeend', fullscreenHTML);
}

// Open verification modal and load data with enhanced error handling
window.openVerificationModal = async function(userId, userType) {
    if (isModalLoading) {
        showNotification('Please wait for the current request to complete', 'warning');
        return;
    }

    currentUserId = userId;
    currentUserType = userType;
    isLandlordView = false; // Admin view
    isModalLoading = true;
    retryCount = 0;

    // Create modal if it doesn't exist
    if (!document.getElementById('verificationModal')) {
        await createVerificationModal();
    }

    // Show loading state and hide content and error states with null checks
    const loadingState = document.getElementById('loadingState');
    const verificationContent = document.getElementById('verificationContent');
    const errorState = document.getElementById('errorState');

    if (loadingState) loadingState.classList.remove('hidden');
    if (verificationContent) verificationContent.classList.add('hidden');
    if (errorState) errorState.classList.add('hidden');

    // Show modal
    const modal = document.getElementById('verificationModal');
    if (modal) {
        modal.classList.remove('hidden');
    }

    // Fetch verification details from backend with retry mechanism
    await loadVerificationData(userId);
};

// Open landlord's own verification modal
window.openLandlordVerificationModal = async function() {
    if (isModalLoading) {
        showNotification('Please wait for the current request to complete', 'warning');
        return;
    }

    isLandlordView = true; // Landlord view
    isModalLoading = true;
    retryCount = 0;

    // Create modal if it doesn't exist
    if (!document.getElementById('verificationModal')) {
        await createVerificationModal();
    }

    // Show loading state and hide content and error states with null checks
    const loadingState = document.getElementById('loadingState');
    const verificationContent = document.getElementById('verificationContent');
    const errorState = document.getElementById('errorState');

    if (loadingState) loadingState.classList.remove('hidden');
    if (verificationContent) verificationContent.classList.add('hidden');
    if (errorState) errorState.classList.add('hidden');

    // Show modal
    const modal = document.getElementById('verificationModal');
    if (modal) {
        modal.classList.remove('hidden');
    }

    // Fetch landlord's own verification details
    await loadLandlordVerificationData();
};

// Enhanced data loading with retry mechanism
async function loadVerificationData(userId) {
    try {
        const response = await fetch(`/admin/users/${userId}/verification-details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            signal: AbortSignal.timeout(10000) // 10 second timeout
        });

        const data = await response.json();

        if (response.ok && data.success) {
            populateVerificationData(data.data);
            showNotification('Verification details loaded successfully', 'success');
        } else {
            throw new Error(data.message || 'Failed to load verification details');
        }
    } catch (error) {
        console.error('Error loading verification details:', error);

        if (error.name === 'TimeoutError') {
            showError('Request timed out. Please check your connection and try again.');
        } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
            showError('Network connection error. Please check your internet connection.');
        } else {
            showError(error.message || 'An unexpected error occurred while loading data');
        }

        // Auto-retry for network errors (up to MAX_RETRIES)
        if (retryCount < MAX_RETRIES && (error.message.includes('Network') || error.name === 'TimeoutError')) {
            retryCount++;
            showNotification(`Retrying... (${retryCount}/${MAX_RETRIES})`, 'info');
            setTimeout(() => loadVerificationData(userId), 2000 * retryCount); // Exponential backoff
        }
    } finally {
        isModalLoading = false;
    }
}

// Populate modal with verification data including image preview
function populateVerificationData(data) {
    // Helper function to safely set text content
    function setTextContent(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        } else {
            console.warn(`Element with ID '${elementId}' not found in modal`);
        }
    }

    setTextContent('userId', data.user_id || '-');
    setTextContent('userType', data.role || '-');

    // Populate other fields with null checks
    setTextContent('userName', data.name || '-');
    setTextContent('userEmail', data.email || '-');
    setTextContent('userPhone', data.phone || '-');
    setTextContent('userCreatedAt', data.created_at ? new Date(data.created_at).toLocaleDateString() : '-');

    // Handle document type - format to user-friendly label
    const documentType = formatDocumentType(data.document_type);
    setTextContent('documentType', documentType);



    // Document preview image
    const documentPreview = document.getElementById('documentPreview');
    if (documentPreview) {
        if (data.front_image_url || data.back_image_url) {
            let imagesHtml = '';

            if (data.front_image_url && data.back_image_url) {
                // Both images available - display side by side
                imagesHtml = `
                    <div class="flex justify-center items-start space-x-6">
                        <div class="flex-1 max-w-xs">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Front Side</span>
                            </div>
                            <div class="relative">
                                <img src="${data.front_image_url}" alt="Verification Document Front" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${data.front_image_url}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${data.front_image_url}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Back Side</span>
                            </div>
                            <div class="relative">
                                <img src="${data.back_image_url}" alt="Verification Document Back" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${data.back_image_url}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${data.back_image_url}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Only one image available - center it
                const imageUrl = data.front_image_url || data.back_image_url;
                const sideLabel = data.front_image_url ? 'Front Side' : 'Back Side';

                imagesHtml = `
                    <div class="flex justify-center">
                        <div class="max-w-sm">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">${sideLabel}</span>
                            </div>
                            <div class="relative">
                                <img src="${imageUrl}" alt="Verification Document ${sideLabel}" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${imageUrl}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${imageUrl}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            documentPreview.innerHTML = imagesHtml;
        } else {
            documentPreview.innerHTML = `
                <div class="text-center text-gray-500">
                    <svg class="h-20 w-20 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-base">No document available</p>
                </div>
            `;
        }
    }

    // Show content, hide loading and error states
    const loadingState = document.getElementById('loadingState');
    const verificationContent = document.getElementById('verificationContent');
    const errorState = document.getElementById('errorState');

    if (loadingState) loadingState.classList.add('hidden');
    if (verificationContent) verificationContent.classList.remove('hidden');
    if (errorState) errorState.classList.add('hidden');
}

// Show error state in modal
function showError(message) {
    alert(message);

    // Helper function to safely manipulate element classes
    function safeClassList(elementId, action, className) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList[action](className);
        } else {
            console.warn(`Element with ID '${elementId}' not found in modal`);
        }
    }

    // Helper function to safely set text content
    function safeSetText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        } else {
            console.warn(`Element with ID '${elementId}' not found in modal`);
        }
    }

    safeClassList('loadingState', 'add', 'hidden');
    safeClassList('verificationContent', 'add', 'hidden');
    safeClassList('errorState', 'remove', 'hidden');
    safeSetText('errorMessage', message);
}

window.closeVerificationModal = function() {
    const modal = document.getElementById('verificationModal');
    const blurBackgrounds = document.querySelectorAll('.fixed.inset-0.bg-gray-900.bg-opacity-20.backdrop-blur-sm.z-40');
    const cancelButton = document.getElementById('cancelButton');

    if (modal) {
        // Completely remove the modal from DOM instead of just hiding it
        modal.remove();
    }

    // Remove all blur background layers
    blurBackgrounds.forEach(bg => bg.remove());

    // Also remove any remaining blur backgrounds that might have been missed
    const allBlurBackgrounds = document.querySelectorAll('[class*="bg-gray-900"][class*="bg-opacity-20"][class*="backdrop-blur-sm"]');
    allBlurBackgrounds.forEach(bg => bg.remove());

    if (cancelButton) {
        cancelButton.classList.add('hidden');
    }
    currentUserId = null;
    currentUserType = null;
};

// Add event listener to the Cancel (X) button to close the modal
document.addEventListener('click', function(event) {
    const cancelButton = document.getElementById('cancelButton');
    if (cancelButton && (cancelButton === event.target || cancelButton.contains(event.target))) {
        window.closeVerificationModal();
    }
});

// Also add direct event listener when modal is created
function addCancelButtonListener() {
    const cancelButton = document.getElementById('cancelButton');
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            window.closeVerificationModal();
        });
    }
}

// Approve verification
window.approveVerification = async function() {
    if (!currentUserId || !currentUserType) {
        alert('No user selected for verification');
        return;
    }

    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');

    // Show progress indicator
    showProgressIndicator('approveBtn', 'Approving verification...', 'Processing request...');

    approveBtn.disabled = true;
    rejectBtn.disabled = true;

    try {
        // Simulate progress steps
        await updateProgress('Validating user data...', 25);
        await updateProgress('Checking verification documents...', 50);
        await updateProgress('Updating user status...', 75);

        const response = await fetch(`/admin/verification/approve/${currentUserId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            await updateProgress('Verification approved successfully!', 100);
            setTimeout(() => {
                alert(data.message || 'Verification approved successfully!');
                closeVerificationModal();
                window.location.reload();
            }, 500);
        } else {
            hideProgressIndicator('approveBtn');
            alert(data.message || 'Failed to approve verification');
        }
    } catch (error) {
        console.error('Error approving verification:', error);
        hideProgressIndicator('approveBtn');
        alert('An error occurred while approving verification');
    } finally {
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
    }
};

// Reject verification
window.rejectVerification = async function() {
    if (!currentUserId || !currentUserType) {
        alert('No user selected for verification');
        return;
    }

    const notes = prompt('Please provide a reason for rejection (optional):');

    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');

    // Show progress indicator
    showProgressIndicator('rejectBtn', 'Rejecting verification...', 'Processing request...');

    approveBtn.disabled = true;
    rejectBtn.disabled = true;

    try {
        // Simulate progress steps
        await updateProgress('Validating rejection request...', 25);
        await updateProgress('Recording rejection notes...', 50);
        await updateProgress('Updating user status...', 75);

        const response = await fetch(`/admin/verification/reject/${currentUserId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notes: notes })
        });

        const data = await response.json();

        if (response.ok) {
            await updateProgress('Verification rejected successfully!', 100);
            setTimeout(() => {
                alert(data.message || 'Verification rejected successfully!');
                closeVerificationModal();
                window.location.reload();
            }, 500);
        } else {
            hideProgressIndicator('rejectBtn');
            alert(data.message || 'Failed to reject verification');
        }
    } catch (error) {
        console.error('Error rejecting verification:', error);
        hideProgressIndicator('rejectBtn');
        alert('An error occurred while rejecting verification');
    } finally {
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
    }
};

// Fullscreen document functions
window.openDocumentFullscreen = function(imageUrl) {
    const modal = document.getElementById('documentFullscreenModal');
    const image = document.getElementById('fullscreenDocumentImage');
    const verificationModal = document.getElementById('verificationModal');

    if (modal && image) {
        image.src = imageUrl;
        modal.classList.remove('hidden');

        // Temporarily hide the verification modal to ensure fullscreen modal appears on top
        if (verificationModal) {
            verificationModal.style.display = 'none';
        }
    }
};

// Close fullscreen document modal
window.closeDocumentFullscreen = function() {
    const modal = document.getElementById('documentFullscreenModal');
    const verificationModal = document.getElementById('verificationModal');

    if (modal) {
        modal.classList.add('hidden');

        // Show the verification modal again
        if (verificationModal) {
            verificationModal.style.display = '';
        }
    }
};

// Retry loading verification data
window.retryLoadVerification = function() {
    if (currentUserId && currentUserType) {
        openVerificationModal(currentUserId, currentUserType);
    }
};

// Progress indicator helper functions
function showProgressIndicator(buttonId, title, subtitle) {
    const button = document.getElementById(buttonId);
    if (!button) return;

    button.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
            </div>
            <div class="flex-1 text-left">
                <div class="text-sm font-medium">${title}</div>
                <div class="text-xs opacity-90" id="${buttonId}ProgressText">${subtitle}</div>
            </div>
        </div>
        <div class="mt-2 w-full bg-white/10 rounded-full h-1">
            <div class="bg-white h-1 rounded-full transition-all duration-300" id="${buttonId}ProgressBar" style="width: 0%"></div>
        </div>
    `;
}

function updateProgress(message, percentage) {
    const progressText = document.getElementById('approveBtnProgressText') || document.getElementById('rejectBtnProgressText');
    const progressBar = document.getElementById('approveBtnProgressBar') || document.getElementById('rejectBtnProgressBar');

    if (progressText) progressText.textContent = message;
    if (progressBar) progressBar.style.width = `${percentage}%`;

    return new Promise(resolve => setTimeout(resolve, 800)); // Simulate processing time
}

function hideProgressIndicator(buttonId) {
    const button = document.getElementById(buttonId);
    if (!button) return;

    if (buttonId === 'approveBtn') {
        button.innerHTML = `
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Approve
        `;
    } else if (buttonId === 'rejectBtn') {
        button.innerHTML = `
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Reject
        `;
    }
}

// Create landlord verification modal HTML
function createLandlordVerificationModal() {
    const modalHTML = `
        <!-- Blur Background Layer -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-20 backdrop-blur-sm z-40"></div>

        <div id="verificationModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-4xl shadow-2xl rounded-2xl bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h3 class="text-2xl font-extrabold text-gray-900 tracking-wide">My Verification Status</h3>
                        <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="flex flex-col items-center justify-center py-20 space-y-6">
                        <div class="relative">
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-b-4 border-blue-600"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-blue-200 animate-ping opacity-20"></div>
                        </div>
                        <div class="text-center space-y-2">
                            <span class="text-xl font-semibold text-gray-700">Loading verification details...</span>
                            <p class="text-sm text-gray-500">Please wait while we fetch your information</p>
                        </div>
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>

                    <!-- Content State -->
                    <div id="verificationContent" class="hidden p-6 space-y-6">
                        <!-- Verification Status Banner -->
                        <div id="statusBanner" class="p-4 rounded-lg border-l-4">
                            <!-- Status content will be populated here -->
                        </div>

                        <!-- User Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-5 bg-gray-50 p-5 rounded-xl shadow-inner border border-gray-200">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Verification ID</label>
                                    <p id="verificationId" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Name</label>
                                    <p id="userName" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Email</label>
                                    <p id="userEmail" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Phone</label>
                                    <p id="userPhone" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                            </div>
                            <div class="space-y-5 bg-gray-50 p-5 rounded-xl shadow-inner border border-gray-200">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Document Type</label>
                                    <p id="documentType" class="mt-1 text-base text-gray-900">Not specified</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Submitted At</label>
                                    <p id="submittedAt" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Verified At</label>
                                    <p id="verifiedAt" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Verification Notes</label>
                                    <p id="verificationNotes" class="mt-1 text-base text-gray-900">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Preview -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Submitted Documents</label>
                            <div id="documentPreview" class="border border-gray-300 rounded-lg p-5 bg-gray-50 shadow-sm">
                                <!-- Document content will be populated here -->
                            </div>
                        </div>

                        <!-- Verification History -->
                        <div id="verificationHistory" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Verification History</label>
                            <div id="historyContent" class="border border-gray-300 rounded-lg p-5 bg-gray-50 shadow-sm max-h-48 overflow-y-auto">
                                <!-- History content will be populated here -->
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button onclick="closeVerificationModal()" class="px-5 py-3 bg-gray-500 text-white rounded-2xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 transition-transform transform hover:scale-105 shadow-lg">
                                <svg class="h-6 w-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Close
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" class="hidden p-6 text-center">
                        <div class="text-red-600 mb-6">
                            <svg class="h-14 w-14 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p id="errorMessage" class="text-xl font-semibold">An error occurred</p>
                        </div>
                        <button onclick="retryLoadVerification()" class="px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if it exists
    const existingModal = document.getElementById('verificationModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Inject modal into body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Create fullscreen modal separately and append to body
    createFullscreenModal();
}

// Load landlord's own verification data
async function loadLandlordVerificationData() {
    try {
        const response = await fetch('/landlord/verification-data', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            signal: AbortSignal.timeout(10000) // 10 second timeout
        });

        const data = await response.json();

        if (response.ok && data.success) {
            populateLandlordVerificationData(data.data);
            showNotification('Verification details loaded successfully', 'success');
        } else {
            throw new Error(data.message || 'Failed to load verification details');
        }
    } catch (error) {
        console.error('Error loading landlord verification details:', error);

        if (error.name === 'TimeoutError') {
            showError('Request timed out. Please check your connection and try again.');
        } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
            showError('Network connection error. Please check your internet connection.');
        } else {
            showError(error.message || 'An unexpected error occurred while loading data');
        }

        // Auto-retry for network errors (up to MAX_RETRIES)
        if (retryCount < MAX_RETRIES && (error.message.includes('Network') || error.name === 'TimeoutError')) {
            retryCount++;
            showNotification(`Retrying... (${retryCount}/${MAX_RETRIES})`, 'info');
            setTimeout(() => loadLandlordVerificationData(), 2000 * retryCount); // Exponential backoff
        }
    } finally {
        isModalLoading = false;
    }
}

// Populate landlord verification modal with data
function populateLandlordVerificationData(data) {
    // Helper function to safely set text content
    function setTextContent(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        } else {
            console.warn(`Element with ID '${elementId}' not found in modal`);
        }
    }

    // Set verification ID
    setTextContent('verificationId', data.verification_id || 'Not assigned');

    // Populate other fields
    setTextContent('userName', data.name || '-');
    setTextContent('userEmail', data.email || '-');
    setTextContent('userPhone', data.phone || '-');

    // Handle document type - format to user-friendly label
    const documentType = formatDocumentType(data.document_type);
    setTextContent('documentType', documentType);

    setTextContent('submittedAt', data.submitted_at || '-');
    setTextContent('verifiedAt', data.verified_at || '-');
    setTextContent('verificationNotes', data.verification_notes || '-');

    // Status banner
    const statusBanner = document.getElementById('statusBanner');
    let statusClass = '';
    let statusIcon = '';
    let statusMessage = '';

    switch (data.verification_status) {
        case 'approved':
            statusClass = 'bg-green-100 border-green-500 text-green-800';
            statusIcon = `
                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            statusMessage = 'Your account has been verified successfully!';
            break;
        case 'pending':
            statusClass = 'bg-yellow-100 border-yellow-500 text-yellow-800';
            statusIcon = `
                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            statusMessage = 'Your verification is under review. Please wait for admin approval.';
            break;
        case 'rejected':
            statusClass = 'bg-red-100 border-red-500 text-red-800';
            statusIcon = `
                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            `;
            statusMessage = 'Your verification was rejected. Please check the notes and resubmit.';
            break;
        default:
            statusClass = 'bg-gray-100 border-gray-500 text-gray-800';
            statusIcon = `
                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            statusMessage = 'No verification submitted yet.';
    }

    if (statusBanner) {
        statusBanner.className = `p-4 rounded-lg border-l-4 ${statusClass}`;
        statusBanner.innerHTML = `
            <div class="flex items-center">
                ${statusIcon}
                <div>
                    <h4 class="text-lg font-semibold">Verification Status: ${data.verification_status ? data.verification_status.charAt(0).toUpperCase() + data.verification_status.slice(1) : 'Not Submitted'}</h4>
                    <p class="text-sm">${statusMessage}</p>
                </div>
            </div>
        `;
    } else {
        console.warn('Status banner element not found in modal');
    }

    // Document preview
    const documentPreview = document.getElementById('documentPreview');
    if (documentPreview) {
        if (data.front_image_url || data.back_image_url) {
            let imagesHtml = '';

            if (data.front_image_url && data.back_image_url) {
                // Both images available - display side by side
                imagesHtml = `
                    <div class="flex justify-center items-start space-x-6">
                        <div class="flex-1 max-w-xs">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Front Side</span>
                            </div>
                            <div class="relative">
                                <img src="${data.front_image_url}" alt="Verification Document Front" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${data.front_image_url}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${data.front_image_url}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Back Side</span>
                            </div>
                            <div class="relative">
                                <img src="${data.back_image_url}" alt="Verification Document Back" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${data.back_image_url}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${data.back_image_url}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Only one image available - center it
                const imageUrl = data.front_image_url || data.back_image_url;
                const sideLabel = data.front_image_url ? 'Front Side' : 'Back Side';

                imagesHtml = `
                    <div class="flex justify-center">
                        <div class="max-w-sm">
                            <div class="text-center mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">${sideLabel}</span>
                            </div>
                            <div class="relative">
                                <img src="${imageUrl}" alt="Verification Document ${sideLabel}" class="w-full h-auto max-h-48 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow" onclick="openDocumentFullscreen('${imageUrl}')">
                                <div class="mt-3 text-center">
                                    <button onclick="openDocumentFullscreen('${imageUrl}')" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors shadow-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        View Full Size
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            documentPreview.innerHTML = imagesHtml;
        } else {
            documentPreview.innerHTML = `
                <div class="text-center text-gray-500">
                    <svg class="h-20 w-20 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-base">No documents submitted yet</p>
                </div>
            `;
        }
    }

    // Verification history
    const historySection = document.getElementById('verificationHistory');
    const historyContent = document.getElementById('historyContent');

    if (data.verification_history && data.verification_history.length > 0) {
        let historyHtml = '<div class="space-y-3">';
        data.verification_history.forEach(item => {
            historyHtml += `
                <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">${item.action}</p>
                        <p class="text-sm text-gray-500">${item.created_at}</p>
                        ${item.notes ? `<p class="text-sm text-gray-700 mt-1">${item.notes}</p>` : ''}
                        ${item.admin_name ? `<p class="text-xs text-gray-400">By: ${item.admin_name}</p>` : ''}
                    </div>
                </div>
            `;
        });
        historyHtml += '</div>';

        if (historyContent) historyContent.innerHTML = historyHtml;
        if (historySection) historySection.classList.remove('hidden');
    } else {
        if (historySection) historySection.classList.add('hidden');
    }

    // Show content, hide loading and error states
    const loadingState = document.getElementById('loadingState');
    const verificationContent = document.getElementById('verificationContent');
    const errorState = document.getElementById('errorState');

    if (loadingState) loadingState.classList.add('hidden');
    if (verificationContent) verificationContent.classList.remove('hidden');
    if (errorState) errorState.classList.add('hidden');
}
