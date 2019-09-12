# Modern WordPress Starter Theme

## Features
- Uses [Timber](https://www.upstatement.com/timber/) for theme development, using Twig template engine
- Gulp build script
  - PostCSS
  - ES6 with Babel
  - Imagemin
  - TailwindCSS
  - PurgeCSS
  - BrowserSync

## Installation

1. ```composer``` will install Timber as a dependency instead of a plugin
2. ```npm install``` will install all necessary build dependencies

### Development

```npm run dev``` to start BrowserSync server and watch on port 3000

### Production

```npm run build``` will package up all resources and minify within ```dist``` directory

## Nice to haves
- Infinite scrolling on news pages
- Lazy loading of images (loading="lazy") fallback to lazysizes
-


# TODO

- Add ::after pseudo element for 2nd color in each theme
- Content feature: add toggle for larger photo
- Image row: different images sizes / acf toggle


