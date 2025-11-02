console.log('Registering favoritesManager component');

// Check if Alpine is available
if (typeof Alpine !== 'undefined') {
    Alpine.data('favoritesManager', () => ({
    favoriteListingIds: [],
    likedListingIds: [],
    likeCounts: {},
    isAuthenticated: false,

    init() {
        // Initialize favoriteListingIds from a global variable or inline JSON in blade
        if (window.favoriteListingIds) {
            this.favoriteListingIds = window.favoriteListingIds;
        }
        // Initialize likedListingIds from a global variable or inline JSON in blade
        if (window.likedListingIds) {
            this.likedListingIds = window.likedListingIds;
        }
        // For unauthenticated users, load liked listings from localStorage
        if (!this.isAuthenticated) {
            const storedLikes = localStorage.getItem('anonymous_liked_listings');
            if (storedLikes) {
                try {
                    const parsedLikes = JSON.parse(storedLikes);
                    // Merge with server-provided likes (though for public pages, server provides empty array)
                    this.likedListingIds = [...new Set([...this.likedListingIds, ...parsedLikes])];
                } catch (e) {
                    console.error('Error parsing anonymous likes from localStorage:', e);
                }
            }
        }
        // Initialize likeCounts from a global variable or inline JSON in blade
        if (window.likeCounts) {
            this.likeCounts = window.likeCounts;
        }
        // Initialize authentication status
        if (window.isAuthenticated !== undefined) {
            this.isAuthenticated = window.isAuthenticated;
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
        const url = this.isAuthenticated ? `/tenant/favorites/toggle/${listingId}` : `/favorites/toggle/${listingId}`;
        console.log('Making request to:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
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

        if (!this.isAuthenticated) {
            // Handle anonymous like using localStorage
            let anonymousLikes = [];
            try {
                const storedLikes = localStorage.getItem('anonymous_liked_listings');
                if (storedLikes) {
                    anonymousLikes = JSON.parse(storedLikes);
                }
            } catch (e) {
                console.error('Error parsing anonymous likes from localStorage:', e);
            }

            if (isLiked) {
                // Remove like
                this.likedListingIds = this.likedListingIds.filter(id => id !== listingId);
                anonymousLikes = anonymousLikes.filter(id => id !== listingId);
                this.likeCounts[listingId] = (this.likeCounts[listingId] || 1) - 1;
                this.showToast('Property unliked');
            } else {
                // Add like
                this.likedListingIds.push(listingId);
                anonymousLikes.push(listingId);
                this.likeCounts[listingId] = (this.likeCounts[listingId] || 0) + 1;
                this.showToast('Property liked');
            }

            // Save updated likes to localStorage
            try {
                localStorage.setItem('anonymous_liked_listings', JSON.stringify(anonymousLikes));
            } catch (e) {
                console.error('Error saving anonymous likes to localStorage:', e);
            }

            // Trigger Alpine reactivity by replacing the object
            this.likeCounts = {...this.likeCounts};
            return;
        }

        const url = this.isAuthenticated ? `/tenant/likes/toggle/${listingId}` : `/likes/toggle/${listingId}`;
        console.log('Making request to:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({}),
        })
        .then(response => {
            // Check if response is ok before parsing JSON
            if (!response.ok) {
                // If not ok, try to parse error response
                return response.json().then(data => {
                    throw { status: response.status, data: data };
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                if (isLiked) {
                    this.likedListingIds = this.likedListingIds.filter(id => id !== listingId);
                    this.likeCounts[listingId] = (this.likeCounts[listingId] || 1) - 1;
                } else {
                    this.likedListingIds.push(listingId);
                    this.likeCounts[listingId] = (this.likeCounts[listingId] || 0) + 1;
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
            if (error.status === 401 || (error.data && error.data.message && error.data.message.includes('Please log in'))) {
                this.showToast('Please log in to like properties.', 'error');
                // Redirect to login page after a delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                this.showToast('An error occurred. Please try again.', 'error');
            }
        });
    }
}));
} else {
    console.error('Alpine.js is not loaded. favoritesManager component cannot be registered.');
}
