const elixir = require('laravel-elixir');

//require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('app.scss');
       //.webpack('app.js');
    mix.scripts('map_ciclovia.js','public/js/ciclovia.js');
    mix.scripts('map_ciclovia_index.js','public/js/ciclovia-index.js');
    mix.scripts('main.js','public/js/main.js');

    mix.scripts('mibici-index.js','public/js/mibici-index.js');
    mix.scripts('mibici-create.js','public/js/mibici-create.js');
    mix.scripts('mibici-destroy.js','public/js/mibici-destroy.js');
    mix.scripts('mibici-edit.js','public/js/mibici-edit.js');


     //Copia de fuentes bootstrap
     mix.copy(
     	'node_modules/bootstrap-sass/assets/fonts/',
     	'public/fonts'
     );
 });
