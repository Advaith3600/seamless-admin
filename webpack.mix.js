const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

const resolveEntry = path => `./src/resources/assets/src/${path}`;
const resolveOutput = path => `./src/resources/assets/${path}`;

mix
    .sass(resolveEntry('scss/app.scss'), 'css')
    .js(resolveEntry('js/app.js'), resolveOutput('js'))
    .js(resolveEntry('js/type-index.js'), resolveOutput('js'))
    .vue()
    .options({
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })
    .setPublicPath(resolveOutput(''));

if (!mix.inProduction())
    mix.disableSuccessNotifications();
