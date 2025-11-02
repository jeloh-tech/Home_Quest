import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Light mode colors
                'primary': '#3b82f6', // Light blue
                'secondary': '#6b7280', // Gray
                'accent': '#10b981', // Emerald green
                'background': '#e0f2fe', // Light blue background
                'surface': '#ffffff', // White
                'text': '#1e293b', // Dark slate
                'text-secondary': '#6b7280', // Gray
                'border': '#bae6fd', // Light blue border
                // Dark mode colors
                'dark-primary': '#60a5fa', // Lighter blue for dark
                'dark-secondary': '#9ca3af', // Light gray
                'dark-accent': '#34d399', // Light emerald
                'dark-background': '#111827', // Darker background
                'dark-surface': '#1f2937', // Dark surface
                'dark-text': '#f9fafb', // Light text
                'dark-text-secondary': '#d1d5db', // Light secondary text
                'dark-border': '#374151', // Dark border
                // Legacy colors
                'baby-blue': '#87CEEB',
                'dark-blue': '#1E3A8A',
            },
        },
    },

    plugins: [forms, require('tailwind-scrollbar-hide')],
};
