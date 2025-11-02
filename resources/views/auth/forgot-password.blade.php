<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password - Home Quest</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/favicon/logo.png') }}" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    @endif
    <style>
        /* Professional animations and styling */
        .fade-in {
            animation: fadeInUp 1.2s ease-out;
        }

        .slide-up {
            animation: slideUp 1.5s ease-out 0.5s both;
        }

        .animate-fade-in-up {
            animation: fadeInUp 1.5s ease-out 0.3s both;
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

        /* Dark mode initialization */
        .dark {
            color-scheme: dark;
        }

        /* Enhanced form styling */
        .form-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(249,250,251,0.95) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .dark .form-container {
            background: linear-gradient(135deg, rgba(31,41,55,0.95) 0%, rgba(17,24,39,0.95) 100%);
            border: 1px solid rgba(75,85,99,0.3);
        }

        /* Professional input styling */
        .input-field {
            background: rgba(255,255,255,0.8);
            border: 2px solid rgba(156,163,175,0.3);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255,255,255,1);
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .dark .input-field {
            background: rgba(31,41,55,0.8);
            border-color: rgba(75,85,99,0.5);
        }

        .dark .input-field:focus {
            background: rgba(31,41,55,1);
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.1);
        }

        /* Professional button styling */
        .submit-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 6px 20px rgba(59,130,246,0.4);
            transform: translateY(-2px);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        /* Error message styling */
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
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex flex-col">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-12 pt-24">
        <div class="w-full max-w-md">
            <!-- Professional Header Section -->
            <div class="text-center mb-8 fade-in">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Reset Your Password</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>
            </div>

            <!-- Professional Form Container -->
            <div class="form-container rounded-3xl shadow-2xl p-8 slide-up">
                <!-- Session Status -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <input id="email" class="input-field block w-full px-4 py-4 rounded-xl text-sm focus:outline-none transition-all duration-200"
                                   type="email" name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Enter your email address" />
                            <div class="absolute right-3 top-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                        </div>
                        @if($errors->has('email'))
                            <p class="error-message text-red-600 dark:text-red-400 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-button w-full py-4 px-6 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Password Reset Link
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Remember your password?
                        <a href="javascript:void(0)" id="openLoginModal" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors cursor-pointer">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>

            <!-- Professional Footer -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Need help? Contact our support team
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 dark:bg-gray-800 text-center py-4 text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} Home Rental System. All rights reserved.
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Sign in to your account</h2>
                <button id="closeLoginModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                @include('components.login-form')

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">
                            Create one here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Toggle Script -->
    <script>
        // Initialize dark mode
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-toggle-light');
        const darkIcon = document.getElementById('theme-toggle-dark');

        if (themeToggle) {
            let isDark = document.documentElement.classList.contains('dark');

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

        // Login Modal functionality
        const loginModal = document.getElementById('loginModal');
        const openLoginModalBtn = document.getElementById('openLoginModal');
        const closeLoginModalBtn = document.getElementById('closeLoginModal');

        if (openLoginModalBtn && loginModal) {
            openLoginModalBtn.addEventListener('click', (e) => {
                e.preventDefault();
                loginModal.classList.remove('hidden');
                // Focus on email input when modal opens
                const emailInput = loginModal.querySelector('#loginEmail');
                if (emailInput) {
                    setTimeout(() => emailInput.focus(), 100);
                }
            });
        }

        if (closeLoginModalBtn && loginModal) {
            closeLoginModalBtn.addEventListener('click', () => {
                loginModal.classList.add('hidden');
            });
        }

        // Close modal when clicking outside
        if (loginModal) {
            loginModal.addEventListener('click', (e) => {
                if (e.target === loginModal) {
                    loginModal.classList.add('hidden');
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && loginModal && !loginModal.classList.contains('hidden')) {
                loginModal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
