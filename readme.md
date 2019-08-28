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

### Regular Site

- settings (these are hidden and simply cloned to flexible fields)
  - color
  - bgcolor
- hero
  - slider (flickity-pagedots,autoplay5s,next/prev)
    - slide
      - overlay_text
      - complementary_text
      - photo
      - theme
- feature
  - item (up to 4)
    - header
    - excerpt
    - link
    - theme (changes the highlight colour on text)
- stats
  - item (up to 4)
    - size
    - text before
    - info
    - text after
    - color*
    - bgcolor*
- facts
  - slider
    - slide
      - photo
      - title (did you know default)
      - blurb
      - link
      - color*
      - bgcolor*
- slider
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
- pullout
  - slider
    - blurb
    - color
- video
- related
  - items (up to 4)

### Fullpage Modules

- full bleed
  - image
- border image
  - image
  - bgcolor
- text
  - blurb
  - color
  - bgcolor
- gif (use frontend from mapp)
  - gallery
  - speed
- slider
  - slides (clone all other items)



### Todo:

- Add aria-page on menu
