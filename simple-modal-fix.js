// Simple Modal Fix - Add this to your verification page
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Simple modal fix loaded');

    // Handle View Details links
    const viewLinks = document.querySelectorAll('.view-details-link');

    viewLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const landlordData = {
                id: parseInt(this.dataset.landlordId) || 0,
                name: this.dataset.landlordName || 'N/A',
                email: this.dataset.landlordEmail || 'N/A',
                phone: this.dataset.landlordPhone || 'N/A',
                verification_id: this.dataset.verificationId || null,
                verified_at: this.dataset.verifiedAt || null,
                created_at: this.dataset.createdAt || null,
                verification_status: this.dataset.verificationStatus || 'pending',
                valid_id_path: this.dataset.validIdPath || null,
            };

            console.log('📋 Opening modal for:', landlordData.name);

            // Find and open the modal
            const modal = document.getElementById('verification-details-modal');
            if (modal && typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(modal);
                if (alpineData && alpineData.open) {
                    alpineData.open(landlordData);
                    console.log('✅ Modal opened successfully');
                } else {
                    console.error('❌ Alpine data not found');
                }
            } else {
                console.error('❌ Modal or Alpine.js not found');
            }

            return false;
        });
    });

    console.log(`🔧 Fixed ${viewLinks.length} view details links`);
});
