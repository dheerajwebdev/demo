const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: ['./templates/**/*.twig', './web/src/frontend/**/*.vue'],
  theme: {
    container: false,
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ],
}
