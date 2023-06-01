const mix = require('laravel-mix');
require('dotenv').config();

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'))
    .version()
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .options({
        processCssUrls: false,
        postCss: [require('autoprefixer')],
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    })
    .version()
    .browserSync('localhost:8000'); // Utilisez l'adresse localhost avec le port de votre choix

// Ajoutez les variables d'environnement Pusher
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js'),
            },
        },
    })
    .version()
    .options({
        processCssUrls: false,
        postCss: [require('autoprefixer')],
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    })
    .version()
    .browserSync('localhost:8000'); // Utilisez l'adresse localhost avec le port de votre choix
