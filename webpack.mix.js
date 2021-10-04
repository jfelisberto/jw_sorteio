const mix = require('laravel-mix');

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

mix
    .sass('resources/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .sass('resources/scss/fontawesome.scss', 'public/css/fontawesome.css')
    .scripts('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/js/bootstrap.bundle.min.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.min.js')
    .scripts('node_modules/@fortawesome/fontawesome-free/js/all.min.js', 'public/js/fontawesome.min.js')
    .sourceMaps();
