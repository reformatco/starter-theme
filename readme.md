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

## ACF Modules
- hero
  - slider
    - slide
      - header
      - subheader
      - link
      - blurb
      - image
      - color
      - bgcolor (no image)
- content feature
  - item (up to 4)
    - title
    - blurb
    - thumbnail
    - bgcolor
- stats
  - item (up to 4)
    - color
    - size
    - text before
    - info
    - text after
- facts
  - slider
    - slide
      - photo
      - bgcolor (no image)
      - title (did you know default)
      - blurb
      - link
- text_row*
  - columns (up to 2)
    - wysiwyg
- image_row*
  - images (up to 2)
    - image
    - caption
    - crop
- downloads
  - item
    - file
- quote
  - quote
  - cite
- table
  - item
    - title
    - info
- aside
  - slider
    - blurb
    - color
- related
  - items (up to 4)

### Todo:

- Add aria-page on menu
