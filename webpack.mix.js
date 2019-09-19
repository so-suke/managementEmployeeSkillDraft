const mix = require('laravel-mix');

const srcPath = 'resources/ts/';

mix.ts('resources/ts/app.ts', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sass('resources/sass/myapp.scss', 'public/css')
  .webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve('resources/sass'),
        '@src': path.resolve(__dirname, srcPath),
        '@store': path.resolve(__dirname, srcPath + 'store/'),
        '@views': path.resolve(__dirname, srcPath + 'views/'),
        '@components': path.resolve(__dirname, srcPath + 'components/'),
        '@modules': path.resolve(__dirname, srcPath + 'modules/'),
        '@myresources': path.resolve(__dirname, srcPath + 'myresources/'),
      }
    }
  });

mix.browserSync({
  proxy: {
    target: "localhost:8000",
    ws: true,
  },
  files: [
    './app/Http/Controllers/*.php',
    './public/css/*.css',
    './public/js/*.js',
    './resources/views/**/*.blade.php',
    './resources/views/*.blade.php',
    './resources/ts/components/*.vue',
  ],
  watchOptions: {
    usePolling: true,
    interval: 100
  },
  open: "external",
  reloadOnRestart: true
});
