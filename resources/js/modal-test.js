// Modal Test Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Modal test script loaded');

    // Test if Alpine.js is available
    if (typeof Alpine !== 'undefined') {
        console.log('Alpine.js is available');
    } else {
        console.log('Alpine.js is NOT available');
    }

    // Test if CSRF token is available
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        console.log('CSRF token found:', csrfToken.getAttribute('content'));
    } else {
        console.log('CSRF token NOT found');
    }

    // Test button functionality
    const testButtons = document.querySelectorAll('.request-sent-button');
    console.log('Found test buttons:', testButtons.length);

    // Add a global test function
    window.testModal = function() {
        console.log('Test modal function called');
        const testData = {
            id: 1,
            name: 'Test Landlord',
            email: 'test@example.com',
            phone: '123-456-7890',
            verification_id: 'TEST123',
            verified_at: null,
            created_at: new Date().toISOString(),
            verification_status: 'pending',
            valid_id_path: null
        };

        const event = new CustomEvent('open-verification-modal', {
            detail: testData,
            bubbles: true,
            cancelable: true
        });

        window.dispatchEvent(event);
        console.log('Test event dispatched');
    };

    // Listen for the modal event
    window.addEventListener('open-verification-modal', function(e) {
        console.log('Modal event received:', e.detail);
    });
});

console.log('Modal test script end');
