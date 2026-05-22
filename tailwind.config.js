import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Support/**/*.php',
    ],

    safelist: [
        { pattern: /bg-(emerald|green|sky|indigo|amber|orange|rose|gray|slate)-(100|200)/ },
        { pattern: /text-(emerald|green|sky|indigo|amber|orange|rose|gray|slate)-(700|800)/ },
        { pattern: /border-(emerald|green|sky|indigo|amber|orange|rose|gray|slate|violet|teal|pink)-(300|400|500)/ },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
