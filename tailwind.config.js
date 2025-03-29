const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: ['./templates/**/*.twig'],
  theme: {
    colors: {
      current: 'currentColor',
      transparent: 'transparent',
      black: '#000',
      white: '#fff',
      neutral: '#e8e8e2',
      blue: {
        200: '#001D3F',
        300: '#425c8f',
        500: '#2C82E9',
        700: '#d3dde3',
      },
      gray: {
        200: '#303030',
        500: '#8e8e8e',
        800: '#F5F5F5',
      },
    },
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '2rem',
        lg: '3rem',
        xl: '4rem',
        '2xl': '5rem',
      },
    },
    fontFamily: {
      sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
      serif: ['Castoro', ...defaultTheme.fontFamily.serif],
    },
    extend: {
      typography: {
        DEFAULT: {
          css: {
            color: '#000',
          },
        },
      },
      screens: {
        'xs': '480px', // Customize the width as needed
      },
    },
  },
  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
