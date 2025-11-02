// Professional Dribbble-Style Scroll Animations JavaScript

class ScrollAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.createScrollProgress();
        this.setupParallax();
        this.setupScrollReveals();
        this.setupStaggeredAnimations();
        this.setupMagneticEffects();
        this.setupIntersectionObserver();
        this.bindEvents();
    }

    // Scroll Progress Indicator
    createScrollProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        document.body.appendChild(progressBar);

        const updateProgress = () => {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            progressBar.style.transform = `scaleX(${scrollPercent / 100})`;
        };

        // Throttled scroll handler for performance
        let ticking = false;
        const handleScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateProgress();
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        updateProgress(); // Initial call
    }

    // Parallax Effects
    setupParallax() {
        const parallaxElements = document.querySelectorAll('.parallax-bg');

        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            element.classList.add(speed < 0.3 ? 'parallax-slow' : speed < 0.7 ? 'parallax-medium' : 'parallax-fast');
        });

        const updateParallax = () => {
            const scrolled = window.pageYOffset;

            parallaxElements.forEach(element => {
                const speed = parseFloat(element.dataset.speed) || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        };

        // Throttled parallax update
        let parallaxTicking = false;
        const handleParallaxScroll = () => {
            if (!parallaxTicking) {
                requestAnimationFrame(() => {
                    updateParallax();
                    parallaxTicking = false;
                });
                parallaxTicking = true;
            }
        };

        window.addEventListener('scroll', handleParallaxScroll, { passive: true });
    }

    // Scroll Reveal Animations
    setupScrollReveals() {
        const revealElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-up, .scroll-reveal-scale');

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Optional: Stop observing after animation
                    // revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(element => {
            revealObserver.observe(element);
        });
    }

    // Staggered Animations
    setupStaggeredAnimations() {
        const staggerContainers = document.querySelectorAll('.stagger-container');

        staggerContainers.forEach(container => {
            const items = container.querySelectorAll('.stagger-item');
            const delay = parseInt(container.dataset.staggerDelay) || 100;

            const containerObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        items.forEach((item, index) => {
                            setTimeout(() => {
                                item.classList.add('active');
                            }, index * delay);
                        });
                        containerObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            containerObserver.observe(container);
        });
    }

    // Magnetic Hover Effects
    setupMagneticEffects() {
        const magneticElements = document.querySelectorAll('.magnetic-element');

        magneticElements.forEach(element => {
            element.addEventListener('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                const deltaX = (e.clientX - centerX) * 0.1;
                const deltaY = (e.clientY - centerY) * 0.1;

                element.style.transform = `translate(${deltaX}px, ${deltaY}px) scale(1.05)`;
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'translate(0, 0) scale(1)';
            });
        });
    }

    // Advanced Intersection Observer for Performance
    setupIntersectionObserver() {
        // Floating elements that should animate on scroll
        const floatingElements = document.querySelectorAll('.floating-scroll');

        const floatingObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                } else {
                    entry.target.classList.remove('active');
                }
            });
        }, {
            threshold: 0.3
        });

        floatingElements.forEach(element => {
            floatingObserver.observe(element);
        });

        // Morphing shapes
        const morphingShapes = document.querySelectorAll('.morphing-shape');

        const morphObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.5
        });

        morphingShapes.forEach(shape => {
            morphObserver.observe(shape);
        });

        // Text reveal animations
        const textReveals = document.querySelectorAll('.text-reveal');

        const textObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.3
        });

        textReveals.forEach(text => {
            textObserver.observe(text);
        });
    }

    // Event Bindings
    bindEvents() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Performance optimization: Pause animations when tab is not visible
        document.addEventListener('visibilitychange', () => {
            const animations = document.querySelectorAll('.floating-scroll, .pulse-on-scroll');
            if (document.hidden) {
                animations.forEach(el => el.style.animationPlayState = 'paused');
            } else {
                animations.forEach(el => el.style.animationPlayState = 'running');
            }
        });

        // Handle resize for responsive parallax
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                // Reinitialize parallax on resize
                this.setupParallax();
            }, 250);
        });
    }

    // Utility method to refresh animations (useful after dynamic content loading)
    refresh() {
        this.setupScrollReveals();
        this.setupStaggeredAnimations();
        this.setupIntersectionObserver();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ScrollAnimations();
});

// Export for potential use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ScrollAnimations;
}
