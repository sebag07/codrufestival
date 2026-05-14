/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './templates/**/*.php',
    './page-templates/**/*.php',
    './template-parts/**/*.php',
    './includes/**/*.php',
    './assets/react-islands/src/**/*.{js,jsx,ts,tsx}',
  ],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '20px',
        sm: '24px',
        lg: '32px',
        xl: '40px',
      },
      screens: {
        sm: '540px',
        md: '720px',
        lg: '960px',
        xl: '1140px',
        '2xl': '1320px',
      },
    },
    extend: {
      colors: {
        codru: {
          main: '#071466',
          secondary: '#12139b',
          accent: '#67e816',
          yellow: '#fbd233',
        },
      },
      spacing: {
        25: '6.25rem',
        30: '7.5rem',
      },
      fontFamily: {
        heading: ['erbaum', 'sans-serif'],
        display: ['neonoir', 'cursive'],
      },
    },
  },
  safelist: [],
  corePlugins: {
    preflight: false,
  },
  plugins: [],
};
