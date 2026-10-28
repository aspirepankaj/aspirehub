import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Modules/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-body)', ...defaultTheme.fontFamily.sans],
                body: ['var(--font-body)', ...defaultTheme.fontFamily.sans],
                jakarta: ['var(--font-body)', ...defaultTheme.fontFamily.sans],
                heading: ['var(--font-heading)', 'sans-serif'],
                display: ['var(--font-heading)', 'sans-serif'],
                momo: ['var(--font-heading)', 'sans-serif'],
            },
            colors: {
                indigo: {
                    DEFAULT: '#135266',
                    50: '#f2f8f9',
                    100: '#deedf0',
                    200: '#c1dee5',
                    300: '#94c6d3',
                    400: '#60a7ba',
                    500: '#135266',
                    600: '#104557',
                    700: '#0e3a4b',
                    800: '#0d3240',
                    900: '#0f2c39',
                    950: '#051b24',
                },
                pink: {
                    DEFAULT: '#135266',
                    50: '#f2f8f9',
                    100: '#deedf0',
                    200: '#c1dee5',
                    300: '#94c6d3',
                    400: '#60a7ba',
                    500: '#135266',
                    600: '#104557',
                    700: '#0e3a4b',
                    800: '#0d3240',
                    900: '#0f2c39',
                    950: '#051b24',
                }
            }
        },
    },

    plugins: [forms],
};
