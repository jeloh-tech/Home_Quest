@props(['show' => false, 'message' => 'Account created successfully!'])

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-init="setTimeout(() => show = false, 3000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-4"
    class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white border border-green-400 rounded-md shadow-lg p-4 flex items-center space-x-3"
    role="alert"
>
    <svg class="h-6 w-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    <p class="text-sm font-medium text-green-700">{{ $message }}</p>
</div>
