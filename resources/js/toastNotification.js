document.addEventListener('DOMContentLoaded', () => {
    const toastMessageElement = document.getElementById('toastMessageData');
    if (toastMessageElement) {
        const toastMessage = toastMessageElement.textContent;
        const toast = document.createElement('div');
        toast.className = 'fixed top-6 right-6 z-50 max-w-md w-full bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 border-2 border-emerald-300 rounded-2xl shadow-2xl p-6 flex items-center space-x-4 transform translate-x-full animate-professional-slide-in backdrop-blur-sm';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.innerHTML = `
            <div class="relative">
                <div class="absolute inset-0 bg-white/20 rounded-full animate-ping"></div>
                <div class="relative bg-white/10 backdrop-blur-sm rounded-full p-3">
                    <svg class="h-8 w-8 text-white flex-shrink-0 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-xl font-bold text-white mb-1 tracking-tight">Welcome Back!</h4>
                <p class="text-emerald-50 text-base font-medium leading-relaxed">${toastMessage}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white/70 hover:text-white transition-colors duration-200 p-1 rounded-full hover:bg-white/10">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        document.body.appendChild(toast);

        // Add professional CSS animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes professional-slide-in {
                0% {
                    transform: translateX(120%) scale(0.8);
                    opacity: 0;
                }
                50% {
                    transform: translateX(-5%) scale(1.02);
                    opacity: 1;
                }
                70% {
                    transform: translateX(2%) scale(0.98);
                }
                100% {
                    transform: translateX(0%) scale(1);
                }
            }
            .animate-professional-slide-in {
                animation: professional-slide-in 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            }

            @keyframes professional-fade-out {
                0% {
                    transform: translateX(0%) scale(1);
                    opacity: 1;
                }
                100% {
                    transform: translateX(120%) scale(0.8);
                    opacity: 0;
                }
            }

            .animate-professional-fade-out {
                animation: professional-fade-out 0.6s ease-in forwards;
            }

            .backdrop-blur-sm {
                backdrop-filter: blur(8px);
            }
        `;
        document.head.appendChild(style);

        // Add entrance sound effect (visual vibration)
        setTimeout(() => {
            toast.style.animation = 'professional-slide-in 0.1s ease-out';
        }, 100);

        // Auto-dismiss after 4 seconds with smooth fade out
        setTimeout(() => {
            toast.style.animation = 'professional-fade-out 0.6s ease-in forwards';
            setTimeout(() => {
                toast.remove();
                // Reload page after toast disappears to reset form and state
                location.reload();
            }, 600);
        }, 4000);

        // Add click outside to dismiss
        const handleClickOutside = (event) => {
            if (!toast.contains(event.target)) {
                toast.style.animation = 'professional-fade-out 0.6s ease-in forwards';
                setTimeout(() => {
                    toast.remove();
                    location.reload();
                }, 600);
                document.removeEventListener('click', handleClickOutside);
            }
        };
        setTimeout(() => {
            document.addEventListener('click', handleClickOutside);
        }, 100);
    }
});
