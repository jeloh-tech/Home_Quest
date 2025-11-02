class CarouselManager {
    constructor() {
        this.currentSlide = 0;
        this.slides = [];
        this.thumbnails = [];
        this.dots = [];
        this.autoPlayInterval = null;
        this.isTransitioning = false;

        this.init();
    }

    init() {
        this.slides = document.querySelectorAll('.carousel-slide');
        this.thumbnails = document.querySelectorAll('.thumbnail-btn');
        this.dots = document.querySelectorAll('.dot-indicator');

        if (this.slides.length === 0) return;

        this.bindEvents();
        this.updateUI();
    }

    bindEvents() {
        // Navigation arrows
        const prevArrow = document.getElementById('prevArrow');
        const nextArrow = document.getElementById('nextArrow');

        if (prevArrow) {
            prevArrow.addEventListener('click', () => this.prevSlide());
        }

        if (nextArrow) {
            nextArrow.addEventListener('click', () => this.nextSlide());
        }

        // Thumbnail buttons
        this.thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => this.goToSlide(index));
        });

        // Dot indicators
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                this.prevSlide();
            } else if (e.key === 'ArrowRight') {
                this.nextSlide();
            }
        });

        // Touch/swipe support
        this.bindTouchEvents();

        // Zoom functionality
        const zoomBtn = document.getElementById('zoomBtn');
        if (zoomBtn) {
            zoomBtn.addEventListener('click', () => this.toggleZoom());
        }
    }

    bindTouchEvents() {
        const carousel = document.getElementById('mainCarousel');
        if (!carousel) return;

        let startX = 0;
        let endX = 0;

        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) { // Minimum swipe distance
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
        });
    }

    goToSlide(index) {
        if (this.isTransitioning || index === this.currentSlide) return;

        this.isTransitioning = true;
        this.currentSlide = index;

        this.updateUI();

        setTimeout(() => {
            this.isTransitioning = false;
        }, 700); // Match transition duration
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }

    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }

    updateUI() {
        // Update slides
        this.slides.forEach((slide, index) => {
            if (index === this.currentSlide) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });

        // Update thumbnails
        this.thumbnails.forEach((thumbnail, index) => {
            if (index === this.currentSlide) {
                thumbnail.classList.remove('border-gray-200', 'dark:border-gray-700', 'hover:border-gray-300', 'dark:hover:border-gray-600');
                thumbnail.classList.add('border-blue-500', 'shadow-lg', 'scale-105');
            } else {
                thumbnail.classList.remove('border-blue-500', 'shadow-lg', 'scale-105');
                thumbnail.classList.add('border-gray-200', 'dark:border-gray-700', 'hover:border-gray-300', 'dark:hover:border-gray-600');
            }
        });

        // Update dots
        this.dots.forEach((dot, index) => {
            if (index === this.currentSlide) {
                dot.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'w-2');
                dot.classList.add('bg-blue-500', 'w-6');
            } else {
                dot.classList.remove('bg-blue-500', 'w-6');
                dot.classList.add('bg-gray-300', 'dark:bg-gray-600', 'w-2');
            }
        });

        // Update counter
        const currentSlideElement = document.getElementById('currentSlide');
        if (currentSlideElement) {
            currentSlideElement.textContent = this.currentSlide + 1;
        }
    }

    toggleZoom() {
        const currentSlideElement = this.slides[this.currentSlide];
        const img = currentSlideElement.querySelector('img');

        if (!img) return;

        if (img.classList.contains('object-cover')) {
            img.classList.remove('object-cover');
            img.classList.add('object-contain');
        } else {
            img.classList.remove('object-contain');
            img.classList.add('object-cover');
        }
    }

    startAutoPlay(interval = 5000) {
        this.stopAutoPlay();
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, interval);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
}

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.carousel-slide')) {
        window.carouselManager = new CarouselManager();
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CarouselManager;
}
