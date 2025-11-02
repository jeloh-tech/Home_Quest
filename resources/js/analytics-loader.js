/**
 * Analytics Dashboard Loading State Manager
 * Handles skeleton screens, progressive loading, and smooth transitions
 */

class AnalyticsLoader {
    constructor() {
        this.charts = new Map();
        this.isLoading = true;
        this.animationDuration = 600;
        this.staggerDelay = 100;
    }

    /**
     * Initialize loading states for all analytics components
     */
    init() {
        this.createSkeletonScreens();
        this.bindEvents();
        this.startLoadingSequence();
    }

    /**
     * Create skeleton screens for all analytics sections
     */
    createSkeletonScreens() {
        // Statistics cards skeleton
        const statsContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4');
        if (statsContainer) {
            this.createStatsSkeleton(statsContainer);
        }

        // Reports analytics skeleton
        const reportsSection = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-5');
        if (reportsSection) {
            this.createReportsSkeleton(reportsSection);
        }

        // Charts skeleton
        const chartContainers = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2 canvas');
        chartContainers.forEach(container => {
            this.createChartSkeleton(container.parentElement);
        });
    }

    /**
     * Create skeleton for statistics cards
     */
    createStatsSkeleton(container) {
        const skeletonHTML = `
            <div class="stagger-item">
                <div class="skeleton-card">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="skeleton skeleton-text skeleton-title w-24 mb-2"></div>
                            <div class="skeleton skeleton-text w-16 h-8 mb-2"></div>
                            <div class="skeleton skeleton-text w-32 h-4"></div>
                        </div>
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <div class="skeleton w-8 h-8 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Replace existing cards with skeletons
        const cards = container.querySelectorAll('.bg-white.rounded-xl');
        cards.forEach(card => {
            card.style.display = 'none';
            card.insertAdjacentHTML('beforebegin', skeletonHTML);
        });
    }

    /**
     * Create skeleton for reports section
     */
    createReportsSkeleton(container) {
        const skeletonHTML = `
            <div class="stagger-item">
                <div class="skeleton-card">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="skeleton skeleton-text skeleton-title w-28 mb-2"></div>
                            <div class="skeleton skeleton-text w-12 h-6 mb-2"></div>
                        </div>
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <div class="skeleton w-6 h-6 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const cards = container.querySelectorAll('.bg-white.rounded-xl');
        cards.forEach(card => {
            card.style.display = 'none';
            card.insertAdjacentHTML('beforebegin', skeletonHTML);
        });
    }

    /**
     * Create skeleton for chart containers
     */
    createChartSkeleton(container) {
        const chartTitle = container.querySelector('h3');
        const canvas = container.querySelector('canvas');

        if (chartTitle) chartTitle.style.display = 'none';
        if (canvas) canvas.style.display = 'none';

        const skeletonHTML = `
            <div class="skeleton skeleton-chart fade-in"></div>
        `;

        container.insertAdjacentHTML('beforeend', skeletonHTML);
    }

    /**
     * Bind loading and interaction events
     */
    bindEvents() {
        // Export button loading states
        const exportButtons = document.querySelectorAll('.export-btn');
        exportButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleExportClick(e.target);
            });
        });

        // Chart hover effects
        document.addEventListener('chartHover', (e) => {
            this.handleChartHover(e.detail);
        });
    }

    /**
     * Handle export button clicks with loading states
     */
    handleExportClick(button) {
        const originalText = button.textContent;
        button.classList.add('loading');
        button.innerHTML = '<span class="opacity-0">Exporting...</span>';

        // Simulate export process (replace with actual export logic)
        setTimeout(() => {
            button.classList.remove('loading');
            button.textContent = originalText;
            this.showExportSuccess(button);
        }, 2000);
    }

    /**
     * Show export success feedback
     */
    showExportSuccess(button) {
        const successIcon = document.createElement('span');
        successIcon.innerHTML = '✓';
        successIcon.className = 'absolute inset-0 flex items-center justify-center text-green-600 font-bold';
        button.appendChild(successIcon);

        setTimeout(() => {
            successIcon.remove();
        }, 2000);
    }

    /**
     * Handle chart hover interactions
     */
    handleChartHover(detail) {
        // Add custom hover effects for charts
        const chartContainer = detail.chart.canvas.parentElement;
        chartContainer.classList.add('chart-hover-active');

        setTimeout(() => {
            chartContainer.classList.remove('chart-hover-active');
        }, 300);
    }

    /**
     * Start the loading sequence with staggered animations
     */
    startLoadingSequence() {
        // Simulate data loading
        setTimeout(() => {
            this.loadStatisticsCards();
        }, 500);

        setTimeout(() => {
            this.loadReportsSection();
        }, 800);

        setTimeout(() => {
            this.loadCharts();
        }, 1200);
    }

    /**
     * Load statistics cards with animation
     */
    loadStatisticsCards() {
        const skeletonCards = document.querySelectorAll('.stagger-item');
        const realCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 .bg-white.rounded-xl');

        skeletonCards.forEach((skeleton, index) => {
            setTimeout(() => {
                skeleton.style.opacity = '0';
                setTimeout(() => {
                    skeleton.remove();
                    if (realCards[index]) {
                        realCards[index].style.display = '';
                        realCards[index].classList.add('fade-in');
                        this.animateCounter(realCards[index]);
                    }
                }, 300);
            }, index * this.staggerDelay);
        });
    }

    /**
     * Load reports section with animation
     */
    loadReportsSection() {
        const skeletonCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-5 .stagger-item');
        const realCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-5 .bg-white.rounded-xl');

        skeletonCards.forEach((skeleton, index) => {
            setTimeout(() => {
                skeleton.style.opacity = '0';
                setTimeout(() => {
                    skeleton.remove();
                    if (realCards[index]) {
                        realCards[index].style.display = '';
                        realCards[index].classList.add('fade-in-delayed');
                        this.animateCounter(realCards[index]);
                    }
                }, 300);
            }, index * this.staggerDelay);
        });
    }

    /**
     * Load charts with smooth transitions
     */
    loadCharts() {
        const chartContainers = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2');

        chartContainers.forEach((container, index) => {
            setTimeout(() => {
                const skeleton = container.querySelector('.skeleton-chart');
                const title = container.querySelector('h3');
                const canvas = container.querySelector('canvas');

                if (skeleton) {
                    skeleton.style.opacity = '0';
                    setTimeout(() => {
                        skeleton.remove();
                        if (title) {
                            title.style.display = '';
                            title.classList.add('fade-in');
                        }
                        if (canvas) {
                            canvas.style.display = '';
                            canvas.classList.add('fade-in-slow');
                        }
                    }, 300);
                }
            }, index * 200);
        });

        this.isLoading = false;
    }

    /**
     * Animate counter values
     */
    animateCounter(card) {
        const counterElement = card.querySelector('.text-3xl.font-bold');
        if (!counterElement) return;

        const targetValue = parseInt(counterElement.textContent.replace(/,/g, ''));
        if (isNaN(targetValue)) return;

        let currentValue = 0;
        const increment = targetValue / 60; // 60 frames for smooth animation
        const duration = 1000; // 1 second
        const stepTime = duration / 60;

        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }

            // Format number with commas
            counterElement.textContent = Math.floor(currentValue).toLocaleString();
        }, stepTime);
    }

    /**
     * Refresh data with loading states
     */
    refresh() {
        this.isLoading = true;
        this.createSkeletonScreens();
        this.startLoadingSequence();
    }

    /**
     * Check if currently loading
     */
    isCurrentlyLoading() {
        return this.isLoading;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.analytics-dashboard')) {
        const loader = new AnalyticsLoader();
        loader.init();

        // Make loader available globally for debugging
        window.analyticsLoader = loader;
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AnalyticsLoader;
}
