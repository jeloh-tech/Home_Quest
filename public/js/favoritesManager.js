console.log('Registering favoritesManager component');

// Check if Alpine is available
if (typeof Alpine !== 'undefined') {
    Alpine.data('favoritesManager', () => ({
    favoriteListingIds: [],
    likedListingIds: [],
    likeCounts: {},

    init() {
        // Initialize favoriteListingIds from a global variable or inline JSON in blade
        if (window.favoriteListingIds) {
            this.favoriteListingIds = window.favoriteListingIds;
        }
        // Initialize likedListingIds from a global variable or inline JSON in blade
        if (window.likedListingIds) {
            this.likedListingIds = window.likedListingIds;
        }
        // Initialize likeCounts from a global variable or inline JSON in blade
        if (window.likeCounts) {
            this.likeCounts = window.likeCounts;
        }
    },

    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-5 right-5 z-50 max-w-sm w-full bg-white border rounded-md shadow-lg p-4 flex items-center space-x-3 ${type === 'success' ? 'border-green-400' : 'border-red-400'}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `<svg class="h-6 w-6 ${type === 'success' ? 'text-green-500' : 'text-red-500'} flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}" />
        </svg>
        <p class="text-sm font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">${message}</p>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 3000);
    },

    toggleFavorite(listingId) {
        console.log('toggleFavorite called with listingId:', listingId);
        const isFavorite = this.favoriteListingIds.includes(listingId);
        const url = `/tenant/favorites/toggle/${listingId}`;
        console.log('Making request to:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                if (isFavorite) {
                    this.favoriteListingIds = this.favoriteListingIds.filter(id => id !== listingId);
                    this.showToast(data.message);
                } else {
                    this.favoriteListingIds.push(listingId);
                    this.showToast(data.message);
                }
            } else {
                this.showToast(data.message, 'error');
                console.error('Failed to toggle favorite:', data.message);
            }
        })
        .catch(error => {
            console.error('Error toggling favorite:', error);
        });
    },

    toggleLike(listingId) {
        console.log('toggleLike called with listingId:', listingId);
        const isLiked = this.likedListingIds.includes(listingId);
        const url = `/tenant/likes/toggle/${listingId}`;
        console.log('Making request to:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                if (isLiked) {
                    this.likedListingIds = this.likedListingIds.filter(id => id !== listingId);
                    this.likeCounts[listingId] = (this.likeCounts[listingId] || 1) - 1;
                    this.showToast(data.message);
                } else {
                    this.likedListingIds.push(listingId);
                    this.likeCounts[listingId] = (this.likeCounts[listingId] || 0) + 1;
                    this.showToast(data.message);
                }
                // Update like count from server if provided
                if (data.like_count !== undefined) {
                    this.likeCounts[listingId] = data.like_count;
                }
                // Trigger Alpine reactivity by replacing the object
                this.likeCounts = {...this.likeCounts};
            } else {
                this.showToast(data.message, 'error');
                console.error('Failed to toggle like:', data.message);
            }
        })
        .catch(error => {
            console.error('Error toggling like:', error);
        });
    }
}));
} else {
    console.error('Alpine.js is not loaded. favoritesManager component cannot be registered.');
}
