<!-- Register Form Component -->
<form id="registerForm" method="POST" action="{{ route('register') }}" class="hidden mt-12" novalidate>
    @csrf
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="registerFirstName" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">First Name</label>
                <input id="registerFirstName" name="first_name" type="text" required
                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                       placeholder="Enter your first name" />
                <p id="registerFirstNameError" class="error-message">First name is required and must be at least 2 characters.</p>
            </div>
            <div>
                <label for="registerSurname" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Surname</label>
                <input id="registerSurname" name="surname" type="text" required
                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                       placeholder="Enter your surname" />
                <p id="registerSurnameError" class="error-message">Surname is required and must be at least 2 characters.</p>
            </div>
        </div>
        <div>
            <label for="registerEmail" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email address</label>
            <input id="registerEmail" name="email" type="email" required
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                   placeholder="Enter your email address" />
            <p id="registerEmailError" class="error-message">Please enter a valid email address.</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="registerRole" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select id="registerRole" name="role" required
                        class="w-full px-3 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm">
                    <option value="">Select role</option>
                    <option value="tenant">Tenant</option>
                    <option value="landlord">Landlord</option>
                </select>
                <p id="registerRoleError" class="error-message">Please select a role.</p>
            </div>
            <div>
                <label for="registerPhone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                <input id="registerPhone" name="phone" type="tel" required
                       class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                       placeholder="+63 9XX XXX XXXX" />
                <p id="registerPhoneError" class="error-message">Please enter a valid phone number.</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="registerPassword" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                <div class="relative">
                    <input id="registerPassword" name="password" type="password" required
                           class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                           placeholder="Min. 8 characters" />
                    <button type="button" id="toggleRegisterPassword" class="absolute right-3 top-2.5 h-5 w-5 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                        <svg id="registerPasswordIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </button>
                </div>
                <p id="registerPasswordError" class="error-message">Password must be at least 8 characters.</p>
            </div>
            <div>
                <label for="registerPasswordConfirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                <div class="relative">
                    <input id="registerPasswordConfirmation" name="password_confirmation" type="password" required
                           class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 text-sm"
                           placeholder="Confirm password" />
                    <button type="button" id="toggleRegisterPasswordConfirmation" class="absolute right-3 top-2.5 h-5 w-5 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                        <svg id="registerPasswordConfirmationIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </button>
                </div>
                <p id="registerPasswordConfirmationError" class="error-message">Passwords do not match.</p>
            </div>
        </div>
        <div class="flex items-start">
            <input type="checkbox" name="terms" id="terms" required
                   class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
            <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                I agree to the <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium transition-colors">Terms & Conditions</a> and <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium transition-colors">Privacy Policy</a>
            </label>
            <p id="termsError" class="error-message">You must agree to the terms and conditions.</p>
        </div>
    </div>
    <button type="submit" class="w-full mt-6 py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
        <span class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Create Account
        </span>
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle for register password
    const registerPasswordInput = document.getElementById('registerPassword');
    const toggleRegisterPasswordButton = document.getElementById('toggleRegisterPassword');
    const registerPasswordIcon = document.getElementById('registerPasswordIcon');

    // Initially hide the toggle button for register password
    toggleRegisterPasswordButton.style.display = 'none';

    // Show toggle button when user starts typing in register password
    registerPasswordInput.addEventListener('input', function() {
        if (registerPasswordInput.value.length > 0) {
            toggleRegisterPasswordButton.style.display = 'block';
        } else {
            toggleRegisterPasswordButton.style.display = 'none';
        }
    });

    // Toggle register password visibility
    toggleRegisterPasswordButton.addEventListener('click', function() {
        if (registerPasswordInput.type === 'password') {
            registerPasswordInput.type = 'text';
            registerPasswordIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        } else {
            registerPasswordInput.type = 'password';
            registerPasswordIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>';
        }
    });

    // Password toggle for register password confirmation
    const registerPasswordConfirmationInput = document.getElementById('registerPasswordConfirmation');
    const toggleRegisterPasswordConfirmationButton = document.getElementById('toggleRegisterPasswordConfirmation');
    const registerPasswordConfirmationIcon = document.getElementById('registerPasswordConfirmationIcon');

    // Initially hide the toggle button for register password confirmation
    toggleRegisterPasswordConfirmationButton.style.display = 'none';

    // Show toggle button when user starts typing in register password confirmation
    registerPasswordConfirmationInput.addEventListener('input', function() {
        if (registerPasswordConfirmationInput.value.length > 0) {
            toggleRegisterPasswordConfirmationButton.style.display = 'block';
        } else {
            toggleRegisterPasswordConfirmationButton.style.display = 'none';
        }
    });

    // Toggle register password confirmation visibility
    toggleRegisterPasswordConfirmationButton.addEventListener('click', function() {
        if (registerPasswordConfirmationInput.type === 'password') {
            registerPasswordConfirmationInput.type = 'text';
            registerPasswordConfirmationIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        } else {
            registerPasswordConfirmationInput.type = 'password';
            registerPasswordConfirmationIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>';
        }
    });
});
</script>
