<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home Quest</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/favicon/logo.png') }}" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
    <!-- Heroicons -->
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/index.js"></script>
    <!-- Scripts -->
@vite(['resources/css/app.css', 'resources/css/modal.css', 'resources/js/app.js', 'resources/js/toastNotification.js', 'resources/js/verification.js', 'resources/js/favoritesManager.js'])
    <style>
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
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen light-blue-bg dark:dark-bg theme-transition">
        @if(!auth()->check())
            @include('layouts.navigation')
        @elseif(auth()->check() && !in_array(auth()->user()->role, ['admin', 'landlord', 'tenant']))
            @include('layouts.navigation')
        @endif
        <!-- Page Heading -->
        @isset($header)
            <header class="bg-surface dark:bg-dark-surface shadow border-b border-border dark:border-dark-border">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        <!-- Page Content -->
        <main class="theme-transition">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

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
                document.getElementById('registerFirstName').focus();
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
                document.getElementById('registerFirstName').focus();
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

</body>
</html>
