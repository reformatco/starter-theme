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

### Todo:

- Add aria-page on menu