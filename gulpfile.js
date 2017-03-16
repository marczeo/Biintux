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

    mix.scripts('map_index.js','public/js/map_index.js');
    mix.scripts('map_route_index.js','public/js/route-index.js');

    mix.scripts('mibici-index.js','public/js/mibici-index.js');
    mix.scripts('mibici-create.js','public/js/mibici-create.js');
    mix.scripts('mibici-destroy.js','public/js/mibici-destroy.js');
    mix.scripts('mibici-edit.js','public/js/mibici-edit.js');

    mix.scripts('user.js', 'public/js/user.js')

    //Copia de jQuery
    mix.copy(
        'node_modules/jquery/dist/jquery.min.js',
        'public/js/jquery/jquery.min.js'
    );
    //Copia de fuentes bootstrap
    mix.copy(
    	'node_modules/bootstrap-sass/assets/fonts/',
    	'public/fonts'
    );
    //Copiar bootstrap table https://www.npmjs.com/package/bootstrap-table
    mix.copy(
        'node_modules/bootstrap-table/dist/bootstrap-table.min.css',
        'public/css/bootstrap-table/bootstrap-table.min.css'
    );
    mix.copy(
        'node_modules/bootstrap-table/dist/bootstrap-table.min.js',
        'public/js/bootstrap-table/bootstrap-table.min.js'
    );
    mix.copy(
        'node_modules/bootstrap-table/dist/locale/bootstrap-table-es-MX.min.js',
        'public/js/bootstrap-table/bootstrap-table-es-MX.min.js'
    );
    mix.copy(
        'node_modules/bootstrap-table/dist/locale/bootstrap-table-en-US.min.js',
        'public/js/bootstrap-table/bootstrap-table-en-US.min.js'
    );
    mix.copy(
        'node_modules/bootstrap-table/dist/bootstrap-table-locale-all.min.js',
        'public/js/bootstrap-table/bootstrap-table-locale-all.min.js'
    );
 });
