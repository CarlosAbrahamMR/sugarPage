const mix = require('laravel-mix');


mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
});

mix.setPublicPath('public');


mix.scripts([
    'public/js/vendor/jquery-3.5.1.min.js',
    'public/js/vendor/jquery.cookie.min.js',
    'public/js/plugins/jquery.breakpoints.min.js',
    'public/js/vendor/moment.min.js',
    'public/js/vendor/daterangepicker.min.js',
    'public/js/vendor/bootstrap.bundle.min.js',
    'public/js/vendor/vue.js', // Solo si no usas la versión de NPM
], 'public/js/vendor-bundle.js');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps();
