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
                primary: '#0b0c2f',
                secondary: '#0f1f3b',
                main: {
                    100: "#feecd1",
                    200: "#fddaa3",
                    300: "#fdc776",
                    400: "#fcb548",
                    500: "#fba21a",
                    600: "#c98215",
                    700: "#976110",
                    800: "#64410a",
                    900: "#322005"
                },
                maindark: '#f79704',
                taps: '#eaf4f7',
            },
        },
    },

    plugins: [forms],
};
