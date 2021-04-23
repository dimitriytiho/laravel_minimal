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
    .js('resources/js/before/index.js', 'public/js/before.js')

    .js('resources/js/index.js', 'public/js/app.js')
    .sass('resources/sass/index.scss', 'public/css/app.css')
    .options({
        processCssUrls: false,
    })

    .js('resources/js/admin/index.js', 'public/js/append.js')
    .sass('resources/sass/admin/index.scss', 'public/css/append.css')
    .options({
        processCssUrls: false,
    })
;

/*mix.styles([
    'public/css/vendor/normalize.css',
    'public/css/vendor/videojs.css'
], 'public/css/all.css');*/

/*mix.scripts([
    'public/js/admin.js',
    'public/js/dashboard.js'
], 'public/js/all.js');*/

mix.browserSync('localhost:8000'); // localhost:8888 127.0.0.1:8000

/*mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();*/
