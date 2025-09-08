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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#f4af01',      // Oranje/kots geel
                darkblue: '#0e132e',     // Donker blauw
                titlegray: '#414140',    // Grijs (naam van logo)
                bgwhite: '#f8f9f9',      // Witte achtergrond
            },
        },
    },

    plugins: [forms],
};
