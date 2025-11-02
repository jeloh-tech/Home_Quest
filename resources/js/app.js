import './bootstrap';
import './echo';
import './verification';
import './carouselManager';
import './registration-validation';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Register favoritesManager component
Alpine.data('favoritesManager', () => ({
    favoriteListingIds: [],
    likedListingIds: [],
    likeCounts: {},

    init() {
        if (window.favoriteListingIds) {
            this.favoriteListingIds = window.favoriteListingIds;
        }
        if (window.likedListingIds) {
            this.likedListingIds = window.likedListingIds;
        }
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
                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
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
                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
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
                if (data.like_count !== undefined) {
                    this.likeCounts[listingId] = data.like_count;
                }
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

// Theme toggle functionality
function initThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const lightIcon = document.getElementById('theme-toggle-light');
    const darkIcon = document.getElementById('theme-toggle-dark');

    if (!themeToggle) return;

    // Check for saved theme preference or default to system preference
    let isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        isDark = savedTheme === 'dark';
    }

    function updateTheme() {
        if (isDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            if (lightIcon) lightIcon.classList.remove('hidden');
            if (darkIcon) darkIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            if (lightIcon) lightIcon.classList.add('hidden');
            if (darkIcon) darkIcon.classList.remove('hidden');
        }
    }

    updateTheme();

    themeToggle.addEventListener('click', () => {
        isDark = !isDark;
        updateTheme();
    });
}

// Initialize theme toggle on page load
document.addEventListener('DOMContentLoaded', initThemeToggle);

Alpine.start();
