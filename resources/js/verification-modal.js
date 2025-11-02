document.addEventListener('DOMContentLoaded', function () {
    window.openVerificationModal = function(userId, userType) {
        const modal = document.getElementById('verificationModal');
        const modalLoading = document.getElementById('modalLoading');
        const modalContent = document.getElementById('modalContent');
        const modalError = document.getElementById('modalError');
        const rejectionNotesSection = document.getElementById('rejectionNotesSection');
        const confirmRejectBtn = document.getElementById('confirmRejectBtn');
        const approveBtn = document.getElementById('approveBtn');
        const rejectBtn = document.getElementById('rejectBtn');
        const rejectionNotes = document.getElementById('rejectionNotes');

        // Reset modal state
        modalContent.classList.add('hidden');
        modalLoading.classList.remove('hidden');
        modalError.classList.add('hidden');
        rejectionNotesSection.classList.add('hidden');
        confirmRejectBtn.classList.add('hidden');
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
        rejectionNotes.value = '';

        modal.style.display = 'block';

        // Fetch verification data
        fetch(`/admin/users/${userId}/verification-details`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Failed to load verification data');
                }
                const user = data.data;

                // Fill modal fields
                document.getElementById('userName').textContent = user.name || 'N/A';
                document.getElementById('userEmail').textContent = user.email || 'N/A';
                document.getElementById('userPhone').textContent = user.phone || 'N/A';
                document.getElementById('userRole').textContent = user.role || 'N/A';
                document.getElementById('verificationStatus').textContent = user.verification_status || 'pending';
                document.getElementById('documentType').textContent = user.document_type || 'Not specified';
                document.getElementById('createdAt').textContent = user.created_at || 'N/A';
                document.getElementById('verificationNotes').textContent = user.verification_notes || 'None';

                // Set status color
                const statusSpan = document.getElementById('verificationStatus');
                statusSpan.className = 'px-2 py-1 rounded text-sm font-medium';
                switch (user.verification_status) {
                    case 'approved':
                        statusSpan.classList.add('bg-green-100', 'text-green-800');
                        break;
                    case 'pending':
                        statusSpan.classList.add('bg-yellow-100', 'text-yellow-800');
                        break;
                    case 'declined':
                        statusSpan.classList.add('bg-red-100', 'text-red-800');
                        break;
                    case 'banned':
                        statusSpan.classList.add('bg-gray-300', 'text-gray-600');
                        break;
                    default:
                        statusSpan.classList.add('bg-gray-100', 'text-gray-700');
                }

                // Load images
                const frontImageContainer = document.getElementById('frontImageContainer');
                const backImageContainer = document.getElementById('backImageContainer');

                frontImageContainer.innerHTML = '';
                backImageContainer.innerHTML = '';

                if (user.front_image_url) {
                    const imgFront = document.createElement('img');
                    imgFront.src = user.front_image_url;
                    imgFront.alt = 'ID Front';
                    imgFront.className = 'max-w-full max-h-48 rounded-md mx-auto';
                    frontImageContainer.appendChild(imgFront);
                } else {
                    frontImageContainer.textContent = 'No front image available.';
                }

                if (user.back_image_url) {
                    const imgBack = document.createElement('img');
                    imgBack.src = user.back_image_url;
                    imgBack.alt = 'ID Back';
                    imgBack.className = 'max-w-full max-h-48 rounded-md mx-auto';
                    backImageContainer.appendChild(imgBack);
                } else {
                    backImageContainer.textContent = 'No back image available.';
                }

                modalLoading.classList.add('hidden');
                modalContent.classList.remove('hidden');
            })
            .catch(error => {
                modalLoading.classList.add('hidden');
                modalError.classList.remove('hidden');
                document.getElementById('errorMessage').textContent = error.message || 'An error occurred while loading the verification details.';
            });
    };

    window.closeVerificationModal = function() {
        const modal = document.getElementById('verificationModal');
        modal.style.display = 'none';
    };

    window.showRejectionNotes = function() {
        const rejectionNotesSection = document.getElementById('rejectionNotesSection');
        const confirmRejectBtn = document.getElementById('confirmRejectBtn');
        const approveBtn = document.getElementById('approveBtn');
        const rejectBtn = document.getElementById('rejectBtn');

        rejectionNotesSection.classList.remove('hidden');
        confirmRejectBtn.classList.remove('hidden');
        approveBtn.disabled = true;
        rejectBtn.disabled = true;
    };

    window.approveVerification = function() {
        const userId = document.querySelector('#approveBtn').closest('[data-user-id]')?.dataset.userId;
        if (!userId) {
            alert('User ID not found.');
            return;
        }
        const approveBtn = document.getElementById('approveBtn');
        approveBtn.disabled = true;

        fetch(`/admin/verification/approve/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            approveBtn.disabled = false;
            if (data.success) {
                showToast('Success', data.message, 'success');
                closeVerificationModal();
                // Optionally refresh the page or update the list
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Error', data.message || 'Failed to approve verification.', 'error');
            }
        })
        .catch(() => {
            approveBtn.disabled = false;
            showToast('Error', 'An error occurred while approving verification.', 'error');
        });
    };

    window.rejectVerification = function() {
        const userId = document.querySelector('#confirmRejectBtn').closest('[data-user-id]')?.dataset.userId;
        const rejectionNotes = document.getElementById('rejectionNotes').value.trim();
        if (!userId) {
            alert('User ID not found.');
            return;
        }
        if (!rejectionNotes) {
            alert('Please provide a reason for rejection.');
            return;
        }
        const confirmRejectBtn = document.getElementById('confirmRejectBtn');
        confirmRejectBtn.disabled = true;

        fetch(`/admin/verification/reject/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ notes: rejectionNotes }),
        })
        .then(response => response.json())
        .then(data => {
            confirmRejectBtn.disabled = false;
            if (data.success) {
                showToast('Success', data.message, 'success');
                closeVerificationModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Error', data.message || 'Failed to reject verification.', 'error');
            }
        })
        .catch(() => {
            confirmRejectBtn.disabled = false;
            showToast('Error', 'An error occurred while rejecting verification.', 'error');
        });
    };

    window.showToast = function(title, message, type) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        // Clear previous icon
        toastIcon.innerHTML = '';

        if (type === 'success') {
            toastIcon.innerHTML = `
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
        } else if (type === 'error') {
            toastIcon.innerHTML = `
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
        }

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 4000);
    };

    window.hideToast = function() {
        const toast = document.getElementById('toast');
        toast.classList.add('hidden');
    };
});
