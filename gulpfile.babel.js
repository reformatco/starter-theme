import {
  src,
  dest,
  watch,
  series,
  parallel,
} from 'gulp';
import browsersync from 'browser-sync';
import cssnano from 'gulp-cssnano';
import del from 'del';
import eslint from 'gulp-eslint';
import gulpif from 'gulp-if';
import htmlmin from 'gulp-htmlmin';
import imagemin from 'gulp-imagemin';
import plumber from 'gulp-plumber';
import postcss from 'gulp-postcss';
import purgecss from 'gulp-purgecss';
import replace from 'gulp-replace';
import sass from 'gulp-sass';
import sassGlob from 'gulp-sass-glob';
import sourcemaps from 'gulp-sourcemaps';
import webpack from 'webpack-stream';
import webp from 'gulp-webp';

import config from './package.json';

const server = browsersync.create();
const TerserJSPlugin = require('terser-webpack-plugin');

sass.compiler = require('node-sass');

export const serve = (done) => {
  server.init({
    proxy: config.url,
    port: 3000,
    open: false,
  });
  done();
};

export const reload = (done) => {
  server.reload();
  done();
};

export const clean = () => del(config.assetsPath);

const getStyleExtension = (preprocessor) => {
  const types = {
    sass: 'scss',
    postcss: 'css',
    css: 'css',
  };
  return types[preprocessor];
};

const styleExt = getStyleExtension(config.preprocessor);

export const styles = () => src(`${config.srcPath}/${styleExt}/style.${styleExt}`)
  .pipe(plumber())
  .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.init()))
  .pipe(gulpif(config.preprocessor === 'sass', sassGlob()))
  .pipe(gulpif(config.preprocessor === 'sass', sass()))
  .pipe(postcss())
  .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.write('.')))
  .pipe(dest(`${config.assetsPath}css`))
  .pipe(server.stream());

export const editorStyles = () => src(`${config.srcPath}/css/editor-style.css`)
  .pipe(postcss())
  .pipe(purgecss({ content: [`${config.srcPath}/html/tinymce.html`] }))
  .pipe(cssnano())
  .pipe(dest('./'))
  .pipe(server.stream());

export const adminStyles = () => src(`${config.srcPath}/css/admin.css`)
  .pipe(postcss())
  .pipe(cssnano())
  .pipe(dest(`${config.assetsPath}css`))
  .pipe(server.stream());

export const images = () => src(`${config.srcPath}/img/**/*.{jpg,jpeg,png,svg,gif}`)
  .pipe(gulpif(process.env.NODE_ENV === 'production', imagemin()))
  .pipe(dest(`${config.assetsPath}img`))
  .pipe(webp())
  .pipe(dest(`${config.assetsPath}img`));

export const fonts = () => src(`${config.srcPath}/webfonts/**/*`)
  .pipe(dest(`${config.assetsPath}webfonts`))
  .pipe(server.stream());

export const miscFiles = () => src(['*.pdf', '*.kml'])
  .pipe(dest(config.assetsPath))
  .pipe(server.stream());

export const markup = () => src(['*.html', '*.php'])
  .pipe(replace('dist/img', 'img'))
  .pipe(replace('dist/css/style.css', 'css/style.css'))
  .pipe(replace('dist/js/bundle.js', 'js/bundle.js'))
  .pipe(replace('</html>', '</html>\n<!-- Made by Reformat (reformat.co) -->'))
  .pipe(htmlmin({
    collapseWhitespace: true,
    removeComments: true,
    minifyJS: true }))
  .pipe(dest(config.assetsPath));

const jsFixed = file => file.eslint !== null && file.eslint.fixed;

export const scriptLint = () => src(['./gulpfile.babel.js', `${config.srcPath}/js/app.js`])
  .pipe(plumber())
  .pipe(eslint());

export const scripts = () => src([`${config.srcPath}/js/app.js`])
  .pipe(plumber())
  .pipe(webpack({
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          loader: 'babel-loader',
        },
      ],
    },
    optimization: {
      minimizer: [
        new TerserJSPlugin({},{
          keep_fnames: false
        }),
      ],
    },
    mode: process.env.NODE_ENV ? 'production' : 'development',
    devtool: process.env.NODE_ENV === 'development' ? 'inline-source-map' : true,
    output: { filename: 'bundle.js' },
    externals: { jquery: 'jQuery' },
  }))
  .pipe(dest(`${config.assetsPath}js`))
  .pipe(server.stream());

export const watchFiles = () => {
  watch(`${config.srcPath}css/**/*`, series(styles, editorStyles, adminStyles));
  watch('./tailwind.config.js', series(styles, editorStyles, adminStyles));
  watch(`${config.srcPath}js/**/*`, series(scriptLint, scripts));
  watch(`${config.srcPath}img/**/*`, images);
  watch(['./**/*.php', './**/*.twig'], reload);
};

export const js = series(scriptLint, scripts);
export const dev = series(clean, editorStyles, adminStyles, parallel(styles, images, fonts, scripts), serve, watchFiles);
export const build = series(clean, editorStyles, adminStyles, parallel(styles, images, fonts, scripts), miscFiles);
export default dev;
