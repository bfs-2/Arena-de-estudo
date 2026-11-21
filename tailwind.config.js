import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                arena: {
                    black: '#050308',
                    purple: '#2E0A3F',
                    lilac: '#B58CFF',
                    ice: '#F1E8FB',
                    violet: '#7C3AED',
                    'violet-light': '#A78BFA',
                },
            },
            fontFamily: {
                display: ['"Playfair Display"', 'serif'],
                montaser: ['"Montaser Arabic"', 'Montserrat', 'sans-serif'],
            }
        }
    },


};
