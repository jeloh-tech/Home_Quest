<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Account Created Successfully - Home Rental System</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="w-24 h-24 mx-auto text-green-500" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="4" class="text-green-200 dark:text-green-700"/>
                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="4" class="text-green-500 animate-checkmark"/>
                    <path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="6" fill="none" class="animate-checkmark" style="animation-delay: 0.3s"/>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Account Created Successfully!
            </h1>
            
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Welcome to Home Rental System, {{ $user->first_name ?? 'there' }}! Your account has been created and you're ready to start your journey.
            </p>

            <!-- Next Steps -->
            <div class="space-y-4 mb-8">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 dark:text-green-200 mb-2">What's Next?</h3>
                    <ul class="text-sm text-green-700 dark:text-green-300 space-y-1 text-left">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Login to your new account
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Complete your profile
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Start browsing listings
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="openLoginModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105">
                    Login to Your Account
                </button>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                <p>A confirmation email has been sent to <strong class="text-gray-700 dark:text-gray-300">{{ $user->email ?? 'your email' }}</strong></p>
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
            modalTitle.textContent = 'Log in to your account';
            toggleText.textContent = "Don't have an account?";
            toggleButton.textContent = 'Register';
            openModal();
        });

        toggleButton.addEventListener('click', () => {
            if (loginForm.classList.contains('hidden')) {
                // Switch to login form
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                modalTitle.textContent = 'Log in to your account';
                toggleText.textContent = "Don't have an account?";
                toggleButton.textContent = 'Register';
                openModal();
            }
        });

        closeModal.addEventListener('click', closeModalFunc);
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !authModal.classList.contains('hidden')) {
                closeModalFunc();
            }
        });

        // Disable closing modal on click outside
        authModal.addEventListener('click', (e) => {
            if (e.target === authModal) {
                // Do nothing to prevent closing on outside click
            }
        });
    </script>
</body>
</html>

<edit_file>
<path>app/Http/Controllers/Auth/RegisteredUserController.php</path>
<content>
<<<<<<< SEARCH
        // Redirect to professional success page with user data in session
        return redirect()->route('register.success')->with('registered_user', $user);
=======
        // Instead of redirecting to a separate success page, redirect to home with success flag and user data in session
        return redirect('/')->with('registration_success', true)->with('registered_user', $user);
