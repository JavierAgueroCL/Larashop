import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.blade.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Roboto', 'sans-serif'],
                poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#FF324D', // Shopwise Red
                    50: '#ffe5e9',
                    100: '#ffccd2',
                    200: '#ff99a6',
                    300: '#ff6679',
                    400: '#ff334d',
                    500: '#ff324d',
                    600: '#e6001e',
                    700: '#b30017',
                    800: '#800011',
                    900: '#4d000a',
                },
                secondary: {
                    DEFAULT: '#202325', // Dark text/bg
                }
            },
        },
    },

    plugins: [forms],
};
