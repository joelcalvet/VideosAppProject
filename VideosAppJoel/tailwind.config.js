import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: 'rgb(var(--color-primary) / <alpha-value>)',
                    light: 'rgb(var(--color-primary-light) / <alpha-value>)',
                    dark: 'rgb(var(--color-primary-dark) / <alpha-value>)',
                },
                text: {
                    DEFAULT: 'rgb(var(--color-text) / <alpha-value>)',
                    muted: 'rgb(var(--color-text-muted) / <alpha-value>)',
                },
                background: 'rgb(var(--color-background) / <alpha-value>)',
            },
            borderRadius: {
                DEFAULT: 'var(--radius)',
            },
            boxShadow: {
                DEFAULT: 'var(--shadow)',
                sm: 'var(--shadow-sm)',
                md: 'var(--shadow-md)',
                lg: 'var(--shadow-lg)',
            },
            fontSize: {
                base: 'var(--font-size-base)',
                lg: 'var(--font-size-lg)',
                xl: 'var(--font-size-xl)',
            },
        },
    },

    plugins: [
        forms,
        typography,
        require('@tailwindcss/aspect-ratio'),
    ],
};
