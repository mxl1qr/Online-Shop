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
            // Token mengikuti "Sirkula Material/DESIGN.md"
            colors: {
                navy: { DEFAULT: '#002547', 600: '#000f23' },
                emerald: { DEFAULT: '#006c47', soft: '#85f9bf' },
                ink: '#181c1c',
                muted: '#5f636b',
                line: { DEFAULT: '#e0e3e2', strong: '#c3c6cf' },
                canvas: '#f4f6f5',
                surface: { DEFAULT: '#f7faf9', low: '#f1f4f3' },
                danger: '#ba1a1a',
                gold: '#c9a840',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                label: ['Inter', ...defaultTheme.fontFamily.sans],
                headline: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                meta: ['12px', '16px'],
                small: ['14px', '20px'],
                body: ['16px', '24px'],
            },
            boxShadow: {
                card: '0 1px 2px rgba(0,37,71,.04), 0 4px 16px rgba(0,37,71,.06)',
                float: '0 12px 40px rgba(0,37,71,.16)',
            },
        },
    },

    plugins: [forms],
};
