let mix = require('laravel-mix')

require('./mix')

mix
  .setPublicPath('dist')
  .js('resources/js/filter.js', 'js')
  .vue({ version: 3 })
  .nova('all4running/nova-multi-select-filter')
