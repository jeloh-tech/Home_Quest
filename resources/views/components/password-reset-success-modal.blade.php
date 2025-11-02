@if(session('password_reset_success'))
    <div id="passwordResetSuccessModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm z-[3000]">
        <div id="passwordResetSuccessModalContent" class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-8 text-center max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <!-- Success Icon with Animation -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" class="animate-draw"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 animate-fade-in-up">🔐 PASSWORD RESET SUCCESS! 🎉</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-6 animate-fade-in-up animation-delay-200">
                Your password has been successfully reset! You're now ready to explore amazing rental properties.
            </p>
            <div class="space-y-4">
                <button onclick="closePasswordResetSuccessModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 animate-fade-in-up animation-delay-400">
                    Continue to Home Quest
                </button>
            </div>

            <!-- Auto-close countdown -->
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 animate-fade-in-up animation-delay-600">
                This modal will close automatically in <span id="resetCountdown" class="font-semibold">5</span> seconds
            </div>
        </div>
    </div>

    <style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes draw {
        to {
            stroke-dashoffset: 0;
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animation-delay-200 {
        animation-delay: 0.2s;
    }

    .animation-delay-400 {
        animation-delay: 0.4s;
    }

    .animation-delay-600 {
        animation-delay: 0.6s;
    }

    .animate-draw {
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: draw 1s ease-in-out forwards;
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('passwordResetSuccessModal');
        const modalContent = document.getElementById('passwordResetSuccessModalContent');

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Auto-close countdown
        let countdown = 5;
        const countdownElement = document.getElementById('resetCountdown');
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                closePasswordResetSuccessModal();
            }
        }, 1000);
    });

    function closePasswordResetSuccessModal() {
        const modal = document.getElementById('passwordResetSuccessModal');
        const modalContent = document.getElementById('passwordResetSuccessModalContent');

        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePasswordResetSuccessModal();
        }
    });

    // Close modal on click outside
    document.getElementById('passwordResetSuccessModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('passwordResetSuccessModal')) {
            closePasswordResetSuccessModal();
        }
    });
    </script>
@endif
