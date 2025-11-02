<!-- Enhanced Navbar with Modern Design -->
<nav class="fixed top-0 left-0 right-0 z-[1001] bg-gradient-to-r from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-lg transition-all duration-500 ease-in-out">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 relative">
            <!-- Logo and Brand -->
            <div class="flex items-center relative z-10">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group transition-transform duration-300 hover:scale-105">
                    <div class="relative">
                        <img src="{{ asset('storage/favicon/loga.png') }}" alt="Home Quest Logo" class="h-10 w-10 dark:invert transition-transform duration-300 group-hover:rotate-12">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <span class="font-bold text-xl bg-gradient-to-r from-gray-900 via-blue-600 to-purple-600 dark:from-white dark:via-blue-400 dark:to-purple-400 bg-clip-text text-transparent" style="font-family: 'Inter', sans-serif; font-weight: 700;">Home Quest</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1 relative z-10">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }} relative px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400 group">
                    <span class="relative z-10">Home</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="{{ route('listings') }}" class="nav-link {{ request()->routeIs('listings') ? 'active' : '' }} relative px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400 group">
                    <span class="relative z-10">Listings</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="#about" class="nav-link relative px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400 group">
                    <span class="relative z-10">About</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="#contact" class="nav-link relative px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400 group">
                    <span class="relative z-10">Contact</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 group-hover:w-full transition-all duration-300"></div>
                </a>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4 relative z-10">
                @include('components.auth-section')

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden relative p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition-colors duration-200" aria-label="Toggle mobile menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-200/20 dark:border-gray-700/20">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('/') }}" class="mobile-nav-link {{ request()->is('/') ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} block px-3 py-2 text-base font-semibold text-gray-900 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                    Home
                </a>
                <a href="{{ route('listings') }}" class="mobile-nav-link {{ request()->routeIs('listings') ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} block px-3 py-2 text-base font-semibold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                    Listings
                </a>
                <a href="#about" class="mobile-nav-link block px-3 py-2 text-base font-semibold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                    About
                </a>
                <a href="#contact" class="mobile-nav-link block px-3 py-2 text-base font-semibold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                    Contact
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Enhanced Navbar Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const isExpanded = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', !isExpanded);

                // Animate hamburger icon
                const hamburgerIcon = mobileMenuButton.querySelector('svg');
                if (hamburgerIcon) {
                    if (!isExpanded) {
                        hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                    } else {
                        hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                    const hamburgerIcon = mobileMenuButton.querySelector('svg');
                    if (hamburgerIcon) {
                        hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                }
            });

            // Close mobile menu on navigation
            mobileMenu.addEventListener('click', function(event) {
                if (event.target.classList.contains('mobile-nav-link')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                    const hamburgerIcon = mobileMenuButton.querySelector('svg');
                    if (hamburgerIcon) {
                        hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                }
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        let lastScrollTop = 0;
        const navbar = document.querySelector('nav');

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        // Keyboard navigation for mobile menu
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }
    });
</script>
