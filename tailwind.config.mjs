/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#effbfc',
          100: '#d7f6f8',
          200: '#b2ecf0',
          300: '#7ddbe3',
          400: '#3fc1cc',
          500: '#1da2ad',
          600: '#15818b',
          700: '#11656d',
          800: '#0c5258',
          900: '#07484b',
          950: '#032a2c',
        },
        gold: {
          300: '#fde047',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
        }
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        heading: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
        poppins: ['"Poppins"', 'sans-serif'],
        jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
        times: ['"Times New Roman"', 'Times', 'serif']
      }
    },
  },
  plugins: [],
}
