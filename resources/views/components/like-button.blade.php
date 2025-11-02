<div
    x-data="{
        liked: window.likedListingIds.includes({{ $listing->id }}),
        likeCount: window.likeCounts[{{ $listing->id }}] ?? 0,
        isAuthenticated: window.isAuthenticated,
        toggleLike() {
            // Make an API call to like/unlike the listing (works for both authenticated and unauthenticated users)
            fetch('/likes/toggle/{{ $listing->id }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.liked = data.is_liked;
                    this.likeCount = data.like_count;
                    // Update the global likedListingIds array for consistency
                    if (this.liked && !window.likedListingIds.includes({{ $listing->id }})) {
                        window.likedListingIds.push({{ $listing->id }});
                    } else if (!this.liked) {
                        window.likedListingIds = window.likedListingIds.filter(id => id !== {{ $listing->id }});
                    }
                } else {
                    alert('Failed to update like status.');
                }
            })
            .catch(() => alert('Failed to update like status.'));
        }
    }"
    class="flex items-center space-x-2 cursor-pointer select-none"
    @click.prevent="toggleLike()"
    :aria-pressed="liked.toString()"
    role="button"
    tabindex="0"
    aria-label="Like this property"
>
    <svg
        :class="liked ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-600'"
        xmlns="http://www.w3.org/2000/svg"
        fill="currentColor"
        viewBox="0 0 24 24"
        class="w-6 h-6 transition-colors duration-300"
    >
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
    <span x-text="likeCount" class="text-gray-700 dark:text-gray-300 font-semibold"></span>
</div>
