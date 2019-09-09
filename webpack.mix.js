const mix = require('laravel-mix');

mix.ts('resources/ts/app.ts', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sass('resources/sass/myapp.scss', 'public/css')
  .webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve('resources/sass')
      }
    }
  });

mix.browserSync({
  proxy: {
    target: "localhost:8000",
    ws: true
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
