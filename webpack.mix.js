const mix = require('laravel-mix');


mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
});

mix.setPublicPath('public');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps();
