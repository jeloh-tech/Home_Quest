@props(['show' => false, 'message' => 'Account created successfully!'])

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-on:close-modal.window="show = false"
    class="fixed inset-0 flex items-center justify-center z-50"
    style="background-color: rgba(0, 0, 0, 0.5);"
>
    <div
        class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
    >
        <div class="mb-4">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="text-lg font-semibold text-gray-900">{{ $message }}</p>
        <div class="mt-6">
            <button
                @click="show = false"
                class="inline-flex justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                OK
            </button>
        </div>
    </div>
</div>
