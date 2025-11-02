    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home Quest</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/favicon/logo.png') }}" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/scroll-animations.css', 'resources/js/app.js', 'resources/js/scroll-animations.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('resources/css/scroll-animations.css') }}">
        <script src="{{ asset('resources/js/scroll-animations.js') }}" defer></script>
    @endif
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        /* Enhanced animations */
        .fade-in {
            animation: fadeInUp 1.2s ease-out;
        }

        .slide-up {
            animation: slideUp 1.5s ease-out 0.5s both;
        }

        .animate-fade-in-up {
            animation: fadeInUp 1.5s ease-out 0.3s both;
        }

        .guarantee-modal {
            animation: floatIn 2s ease-out 0.5s both;
        }

        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Dark mode initialization */
        .dark {
            color-scheme: dark;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced floating animations */
        @keyframes float1 {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-10px) rotate(2deg) scale(1.05);
            }
            50% {
                transform: translateY(-20px) rotate(0deg) scale(1.1);
            }
            75% {
                transform: translateY(-10px) rotate(-2deg) scale(1.05);
            }
        }

        @keyframes float2 {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-15px) rotate(-3deg) scale(1.08);
            }
            50% {
                transform: translateY(-30px) rotate(0deg) scale(1.15);
            }
            75% {
                transform: translateY(-15px) rotate(3deg) scale(1.08);
            }
        }

        @keyframes float3 {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-25px) rotate(5deg) scale(1.12);
            }
            50% {
                transform: translateY(-50px) rotate(0deg) scale(1.25);
            }
            75% {
                transform: translateY(-25px) rotate(-5deg) scale(1.12);
            }
        }

        @keyframes float4 {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-20px) translateX(10px) rotate(-4deg) scale(1.1);
            }
            50% {
                transform: translateY(-40px) translateX(0px) rotate(0deg) scale(1.2);
            }
            75% {
                transform: translateY(-20px) translateX(-10px) rotate(4deg) scale(1.1);
            }
        }

        @keyframes float5 {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-30px) translateX(-15px) rotate(6deg) scale(1.15);
            }
            66% {
                transform: translateY(-15px) translateX(15px) rotate(-3deg) scale(1.08);
            }
        }

        @keyframes puzzleFloat {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-15px) translateX(5px) rotate(2deg);
            }
            50% {
                transform: translateY(-30px) translateX(0px) rotate(0deg);
            }
            75% {
                transform: translateY(-15px) translateX(-5px) rotate(-2deg);
            }
        }

        @keyframes geometricPulse {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.1) rotate(180deg);
                opacity: 1;
            }
        }

        .floating-image1 {
            animation: float1 8s ease-in-out infinite;
        }

        .floating-image2 {
            animation: float2 10s ease-in-out infinite;
        }

        .floating-image3 {
            animation: float3 12s ease-in-out infinite;
        }

        .floating-image4 {
            animation: float4 14s ease-in-out infinite;
        }

        .floating-image5 {
            animation: float5 16s ease-in-out infinite;
        }

        .puzzle-piece {
            /* animation: puzzleFloat 10s ease-in-out infinite; */
        }

        .geometric-shape {
            animation: geometricPulse 8s ease-in-out infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Typing animation */
        .typing-cursor {
            border-right: 2px solid #3b82f6;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { border-color: transparent; }
            51%, 100% { border-color: #3b82f6; }
        }

        /* Premium 3D Text Effect */
        .text-3d {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow:
                0 1px 0 #4a5568,
                0 2px 0 #2d3748,
                0 3px 0 #1a202c,
                0 4px 0 #171923,
                0 5px 0 #0f1419,
                0 6px 1px rgba(0,0,0,0.3),
                0 0 5px rgba(0,0,0,0.2),
                0 1px 3px rgba(0,0,0,0.4),
                0 3px 5px rgba(0,0,0,0.3),
                0 5px 10px rgba(0,0,0,0.2),
                0 10px 10px rgba(0,0,0,0.1),
                0 20px 20px rgba(0,0,0,0.05);
            animation: premiumGlow 4s ease-in-out infinite;
        }

        @keyframes premiumGlow {
            0%, 100% {
                background-position: 0% 50%;
                brightness(1);
            }
            50% {
                background-position: 100% 50%;
                brightness(1.05);
            }
        }



        /* Responsive text sizing */
        @media (max-width: 768px) {
            #hello-world-section h2 {
                font-size: 4rem;
            }
            #hello-world-section p {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            #hello-world-section h2 {
                font-size: 3rem;
            }
            #hello-world-section p {
                font-size: 1.25rem;
            }
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        .field-success {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col">
    @include('layouts.navigation')

    <script>
        // Initialize dark mode
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }

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

        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileButton) {
            profileButton.addEventListener('click', () => {
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }
    </script>

    @if(session('status'))
        <span id="toastMessageData" class="hidden">{{ session('status') }}</span>
        <script src="{{ asset('js/toastNotification.js') }}"></script>
    @endif

    <!-- First Section - Welcome -->
    <section class="section min-h-screen flex flex-col items-center justify-center px-4 py-8 bg-blue-50 dark:bg-gray-900 pt-6 relative overflow-hidden parallax-container">
        <!-- Professional Background Elements - Minimal & Clean -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Subtle gradient background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 via-transparent to-purple-50/20 dark:from-gray-900/50 dark:via-transparent dark:to-purple-900/30"></div>

            <!-- Minimal floating orbs -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-400/8 rounded-full blur-2xl floating-scroll" style="animation-delay: 0s;"></div>
            <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-purple-400/6 rounded-full blur-2xl floating-scroll" style="animation-delay: 2s;"></div>

            <!-- Professional image square -->
            <img src="{{ asset('storage/img/oyea.png') }}" alt="Professional Image" class="absolute top-20 -right-32 transform translate-x--1/2 translate-y-0 w-[50rem] h-[50rem] object-contain opacity-40" style="filter: brightness(0.95) contrast(1.05) saturate(1.1); transform: rotate(0deg) scaleX(-1);">

            <!-- Minimal accent elements -->
            <div class="absolute top-1/6 right-1/3 w-16 h-16 bg-gradient-to-br from-slate-300/20 to-slate-400/20 transform rotate-45 floating-scroll" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/4 left-1/6 w-12 h-12 bg-gradient-to-br from-slate-400/15 to-slate-500/15 rounded-full floating-scroll" style="animation-delay: 2.5s;"></div>
        </div>








        <div class="fade-in flex flex-col md:flex-row items-center justify-center z-10 w-full mx-auto ml-0 pl-0">
            <!-- Enhanced Image on the left with professional design elements -->
            <div class="md:w-1/3 mb-8 md:mb-0 -mt-2 relative">


                <!-- Professional image container -->
                <div class="relative z-10 rounded-l-3xl overflow-hidden mt-12">
                    <img src="{{ asset('storage/img/akona.png') }}" alt="Welcome Image" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">

                    <!-- Enhanced overlay with professional elements -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent">
                        <!-- Quality indicators -->
                        <div class="absolute bottom-4 left-4 flex space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                        </div>

                        <!-- Rating stars -->
                        <div class="absolute bottom-4 right-4 flex space-x-1">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                        </div>
                    </div>
                </div>

                <!-- Floating accent elements around the image -->
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-cyan-400/30 to-blue-500/30 rounded-full floating-image4 z-0"></div>
                <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-gradient-to-br from-purple-400/25 to-pink-500/25 transform rotate-12 floating-image5 z-0" style="animation-delay: 2s;"></div>
            </div>


            <!-- Enhanced Right side with professional design -->
            <div class="md:w-2/3 p-8 ml-8 flex flex-col mt-0 h-full relative">
                <!-- Background decorative elements for right side -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-purple-500/10 to-pink-500/10 rounded-full blur-2xl -z-10"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl -z-10"></div>

                <div class="flex flex-col justify-center relative z-10">
                    <!-- Professional title with clean typography -->
                    <h1 id="typing-text" class="text-4xl md:text-6xl font-bold mb-6 text-gray-900 dark:text-white leading-tight" style="font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: -0.02em;"></h1>

                    <!-- Professional description -->
                    <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed max-w-2xl" style="font-family: 'Inter', sans-serif; font-weight: 400; line-height: 1.6;">
                        Find your ideal rental property with our trusted platform. Browse verified listings, connect with reliable landlords, and secure your perfect home with confidence and ease.
                    </p>

                    <!-- Professional Call-to-Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-start items-start mb-8">
                        <a href="{{ route('listings') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                            Browse Properties
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <button onclick="openModal()" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold py-4 px-8 rounded-lg shadow-lg border border-gray-300 dark:border-gray-600 hover:shadow-xl hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 flex items-center justify-center">
                            Get Started
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Professional trust indicators -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="text-center">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">100% Verified</h4>
                            <p>All properties verified for quality</p>
                        </div>
                        <div class="text-center">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Fast Processing</h4>
                            <p>Quick application approvals</p>
                        </div>
                        <div class="text-center">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Expert Support</h4>
                            <p>24/7 customer assistance</p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced floating images section -->
                <div class="-mt-12 flex justify-center relative">
                    <!-- Main location image with enhanced styling -->
                    <div class="relative z-10">
        <img src="{{ asset('storage/img/location.png') }}" alt="Location Image" class="w-full h-full object-cover max-w-lg -mt-10 rounded-2xl">
                        <p class="text-center text-gray-600 dark:text-gray-300 mt-4 text-lg leading-relaxed max-w-md mx-auto">
                            Find your ideal rental property with our trusted platform. Browse verified listings, connect with reliable landlords, and secure your perfect home with confidence and ease.
                        </p>
                    </div>

                    <!-- Enhanced floating property images -->
                    <div class="absolute top-1/4 left-1/4 w-48 h-48 rounded-full shadow-2xl floating-image1 border-4 border-white/30 dark:border-gray-600/30 overflow-hidden">
                        <img src="{{ asset('storage/img/homess.png') }}" alt="Property Image" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute top-1/5 right-1/4 w-40 h-40 rounded-full shadow-2xl floating-image2 border-4 border-white/30 dark:border-gray-600/30 overflow-hidden">
                        <img src="{{ asset('storage/img/homess.png') }}" alt="Property Image" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>

                    <!-- Additional floating elements -->
                    <div class="absolute top-1/3 right-1/6 w-24 h-24 bg-gradient-to-br from-cyan-400/20 to-blue-500/20 rounded-full floating-image3 border-2 border-white/20"></div>
                    <div class="absolute bottom-1/4 left-1/6 w-32 h-32 bg-gradient-to-br from-purple-400/20 to-pink-500/20 transform rotate-12 floating-image4 border-2 border-white/20"></div>
                </div>
            </div>
        </div>


    </section>

    @include('components.hello-world-section')
    @include('components.property-showcase-section')
    @include('components.testimonials-section')
    @include('components.why-choose-property-section')

    <footer class="bg-gray-100 dark:bg-gray-800 text-center py-4 text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} Home Rental System. All rights reserved.
    </footer>

    <!-- Registration Success Modal -->
    @include('components.register-success-modal')

    @if(session('registration_success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Open the registration success modal
                openRegisterSuccessModal();
            });
        </script>
    @endif

    <!-- Password Reset Success Modal -->
    @include('components.password-reset-success-modal')

    <!-- Registration Validation Script -->
    <script>
        // Registration Form Validation
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const loginForm = document.getElementById('loginForm');
            
            if (!registerForm) return;
            
            // Validation functions
            function showError(field, message) {
                const errorElement = document.getElementById(field.id + 'Error');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                    field.classList.add('border-red-500', 'bg-red-50');
                    field.classList.remove('border-gray-300');
                }
            }
            
            function hideError(field) {
                const errorElement = document.getElementById(field.id + 'Error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                    field.classList.remove('border-red-500', 'bg-red-50');
                    field.classList.add('border-gray-300');
                }
            }
            
            function validateField(field, validationFn, errorMessage) {
                const value = field.value.trim();
                if (!validationFn(value)) {
                    showError(field, errorMessage);
                    return false;
                } else {
                    hideError(field);
                    return true;
                }
            }
            
            // Validation rules
            const rules = {
                registerFirstName: {
                    validate: (value) => value.length >= 2,
                    message: 'Min 2 characters.'
                },
                registerSurname: {
                    validate: (value) => value.length >= 2,
                    message: 'Min 2 characters.'
                },
                registerEmail: {
                    validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                    message: 'Invalid email.'
                },
                registerRole: {
                    validate: (value) => value !== '',
                    message: 'Select a role.'
                },
                registerPhone: {
                    validate: (value) => /^[\d\s\-\+\(\)]+$/.test(value) && value.length >= 10,
                    message: 'Invalid phone.'
                },
                registerPassword: {
                    validate: (value) => value.length >= 8,
                    message: 'Min 8 characters.'
                },
                registerPasswordConfirmation: {
                    validate: (value) => value === document.getElementById('registerPassword').value,
                    message: 'Passwords do not match.'
                },
                terms: {
                    validate: () => document.getElementById('terms').checked,
                    message: 'Accept terms.'
                }
            };
            
            // Setup validation
            function setupValidation() {
                Object.keys(rules).forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.addEventListener('blur', () => {
                            validateField(field, rules[fieldId].validate, rules[fieldId].message);
                        });
                        
                        field.addEventListener('input', () => {
                            const errorElement = document.getElementById(fieldId + 'Error');
                            if (errorElement && errorElement.style.display === 'block') {
                                validateField(field, rules[fieldId].validate, rules[fieldId].message);
                            }
                        });
                    }
                });
            }
            
            // Form submission validation
            function validateForm(form) {
                let isValid = true;
                const fields = form.querySelectorAll('input[required], select[required]');
                
                fields.forEach(field => {
                    const fieldId = field.id;
                    if (rules[fieldId]) {
                        if (!validateField(field, rules[fieldId].validate, rules[fieldId].message)) {
                            isValid = false;
                        }
                    }
                });
                
                return isValid;
            }
            
            // Setup form handlers
            setupValidation();
            
            registerForm.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Focus on first error field
                    const firstError = this.querySelector('.error-message[style="display: block;"]');
                    if (firstError) {
                        const field = firstError.previousElementSibling || firstError.previousElementSibling;
                        if (field) field.focus();
                    }
                    return false;
                }
            });
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const emailField = document.getElementById('loginEmail');
                    const passwordField = document.getElementById('loginPassword');
                    
                    let isValid = true;
                    
                    if (!emailField.value.trim()) {
                        showError(emailField, 'Email is required.');
                        isValid = false;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                        showError(emailField, 'Please enter a valid email address.');
                        isValid = false;
                    } else {
                        hideError(emailField);
                    }
                    
                    if (!passwordField.value.trim()) {
                        showError(passwordField, 'Password is required.');
                        isValid = false;
                    } else {
                        hideError(passwordField);
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
            }
        });
    </script>

    <!-- Auth Modal -->
    <div id="authModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 backdrop-blur-md z-[1000]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8 relative transform scale-95 transition-all duration-300 mx-4 border border-gray-200 dark:border-gray-700">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none transition-colors" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Logo/Brand Section -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 dark:text-white">Welcome to Home Quest</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            @include('components.login-form')

            <!-- Register Form -->
            @include('components.register-form')

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    <span id="toggleText">Don't have an account?</span>
                </p>
                <button id="toggleButton" type="button" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-semibold transition-colors text-sm">
                    Create a new account →
                </button>
            </div>
        </div>
    </div>

    <script>
        const authModal = document.getElementById('authModal');
        const closeModal = document.getElementById('closeModal');
        const modalTitle = document.getElementById('modalTitle');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const toggleText = document.getElementById('toggleText');
        const toggleButton = document.getElementById('toggleButton');
        const loginButton = document.getElementById('loginButton');

        function openModal() {
            authModal.classList.remove('hidden');
            authModal.classList.add('flex');
            if (!loginForm.classList.contains('hidden')) {
                document.getElementById('loginEmail').focus();
            } else {
                document.getElementById('registerName').focus();
            }
        }

        function closeModalFunc() {
            authModal.classList.add('hidden');
            authModal.classList.remove('flex');
            // Hide error messages if any
            document.querySelectorAll('#loginEmailError, #registerEmailError').forEach(el => el.classList.add('hidden'));
        }

        loginButton.addEventListener('click', () => {
            // Show login form, hide register form
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            modalTitle.textContent = 'Sign in to your account';
            toggleText.textContent = "Don't have an account?";
            toggleButton.textContent = 'Sign up';
            openModal();
        });

        toggleButton.addEventListener('click', () => {
            if (loginForm.classList.contains('hidden')) {
                // Switch to login form
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                modalTitle.textContent = 'Sign in to your account';
                toggleText.textContent = "Don't have an account?";
                toggleButton.textContent = 'Sign up';
                document.getElementById('loginEmail').focus();
            } else {
                // Switch to register form
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                modalTitle.textContent = 'Create a new account';
                toggleText.textContent = 'Already have an account?';
                toggleButton.textContent = 'Sign in';
                document.getElementById('registerName').focus();
            }
        });

        closeModal.addEventListener('click', closeModalFunc);

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !authModal.classList.contains('hidden')) {
                closeModalFunc();
            }
        });

        // Disable closing modal on click outside modal content
        authModal.addEventListener('click', (e) => {
            if (e.target === authModal) {
                // Do nothing to prevent closing on outside click
                // closeModalFunc();
            }
        });

        // Email validation for login form
        document.getElementById('loginEmail').addEventListener('input', () => {
            const emailInput = document.getElementById('loginEmail');
            const emailError = document.getElementById('loginEmailError');
            if (!emailInput.checkValidity()) {
                emailError.classList.remove('hidden');
            } else {
                emailError.classList.add('hidden');
            }
        });

        // Email validation for register form
        document.getElementById('registerEmail').addEventListener('input', () => {
            const emailInput = document.getElementById('registerEmail');
            const emailError = document.getElementById('registerEmailError');
            if (!emailInput.checkValidity()) {
                emailError.classList.remove('hidden');
            } else {
                emailError.classList.add('hidden');
            }
        });

        // Typing animation for welcome text
        document.addEventListener('DOMContentLoaded', function() {
            const staticText = "Welcome to ";
            const loopText = "Home Quest";
            const typingText = document.getElementById('typing-text');
            let index = 0;
            let isDeleting = false;

            function typeWriter() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                const textClass = isDarkMode
                    ? 'text-white'
                    : 'bg-gradient-to-r from-black via-violet-500 to-black bg-clip-text text-transparent';

                if (!isDeleting && index < loopText.length) {
                    typingText.innerHTML = staticText + '<span style="font-size: 1.2em; font-weight: bolder;" class="' + textClass + '">' + loopText.substring(0, index + 1) + '</span>' + '<span class="typing-cursor"></span>';
                    index++;
                    setTimeout(typeWriter, 150); // Typing speed
                } else if (isDeleting && index > 0) {
                    typingText.innerHTML = staticText + '<span style="font-size: 1.2em; font-weight: bolder;" class="' + textClass + '">' + loopText.substring(0, index - 1) + '</span>' + '<span class="typing-cursor"></span>';
                    index--;
                    setTimeout(typeWriter, 100); // Deleting speed
                } else if (!isDeleting && index === loopText.length) {
                    // Pause at end of text
                    setTimeout(() => {
                        isDeleting = true;
                        typeWriter();
                    }, 2000); // Pause before deleting
                } else if (isDeleting && index === 0) {
                    // Reset for next loop
                    isDeleting = false;
                    setTimeout(typeWriter, 500); // Pause before typing again
                }
            }

            // Start with static text
            typingText.innerHTML = staticText;
            typeWriter();
        });
    </script>
</body>
</html>
