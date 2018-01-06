let mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', 'public/css', {
  includePaths: [
    'node_modules/font-awesome/scss',
    'node_modules/semantic-ui-sass/scss'
  ]
})
  .js('resources/assets/js/app.js', 'public/js/app.js')
  .js('node_modules/semantic-ui-sass/semantic-ui.js', 'public/js/app.js')

mix.inProduction() ? mix.version() : mix.browserSync({ proxy: 'localhost:8000' })
