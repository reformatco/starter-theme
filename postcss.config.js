module.exports = (ctx) => ({
  plugins: {
    'postcss-import-ext-glob': {},
    'postcss-import': {},
    'tailwindcss': './tailwind.config.js',
    'postcss-preset-env': {
      stage: 0
    },
    'postcss-utilities': {},
    'precss': {},
    '@fullhuman/postcss-purgecss': process.env.NODE_ENV === 'production',
    'cssnano': process.env.NODE_ENV === 'production' ? {
      preset: [
        'default',
        {
          calc: false,
          discardComments: {
            removeAll: true
          }
        }
      ]
    } : false,
  }
})