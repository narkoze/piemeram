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

mix.copy('resources/assets/css/font-awesome-animation.min.css', 'public/css/font-awesome-animation.min.css')

mix.combine([
  './node_modules/jquery/dist/jquery.min.js',
  './node_modules/jquery-ujs/src/rails.js'
], 'public/js/libs.js')

mix.js('resources/assets/js/vue.js', 'public/js/vue.js')
mix.js('resources/assets/js/components/modal/modal.js', 'public/js/components/modal/modal.js')
mix.js('resources/assets/js/components/login/login.js', 'public/js/components/login/login.js')
mix.js('resources/assets/js/components/login/register/register.js', 'public/js/components/login/register/register.js')
mix.js('resources/assets/js/components/login/forgotpassword/forgotpassword.js', 'public/js/components/login/forgotpassword/forgotpassword.js')

mix.js('resources/assets/js/axios.js', 'public/js/axios.js')

mix.js('node_modules/semantic-ui-sass/semantic-ui.js', 'public/js/app.js')
mix.js('resources/assets/js/app.js', 'public/js/app.js')

mix.inProduction() ? mix.version() : mix.browserSync({ proxy: 'localhost:8000' })
