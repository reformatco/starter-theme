module.exports = {
  content: ['./**/*.twig', './src/**/*.js'],
  whitelist: ['loading', 'yeah', 'nav-active', 'subnav-active'],
  extractors: [
    {
      extensions: ['twig', 'svg', 'js'],
      extractor: class TailwindExtractor {
        static extract (content) {
          return content.match(/[A-Za-z0-9-_:/]+/g) || []
        }
      },
    },
  ],
}