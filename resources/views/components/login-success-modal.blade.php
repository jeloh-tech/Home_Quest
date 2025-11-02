@if(session('status'))
    <div id="loginSuccessModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm z-[3000]">
        <div id="loginSuccessModalContent" class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-8 text-center max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <!-- Success Icon with Animation -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" class="animate-draw"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 animate-fade-in-up">🎉 SUCCESS! ⭐ ⭐ ⭐</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-6 animate-fade-in-up animation-delay-200">
                Your Account Successfully Logged In!
            </p>
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
        const modal = document.getElementById('loginSuccessModal');
        const modalContent = document.getElementById('loginSuccessModalContent');

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Auto-close after 2 seconds
        setTimeout(() => {
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }, 2000);
    });
    </script>
@endif
