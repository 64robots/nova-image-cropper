const mix = require('laravel-mix')

mix
  .js('resources/js/field.js', 'dist/js')
  .sass('resources/sass/field.scss', 'dist/css')
  .setPublicPath('./')
  .webpackConfig({
    resolve: {
      symlinks: false
    }
  })
  .babelConfig({
    plugins: [
      [
        'component',
        {
          libraryName: 'element-ui',
          styleLibraryName: 'theme-chalk'
        }
      ]
    ]
  })
