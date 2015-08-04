// var elixir = require('laravel-elixir');
var htmlmin = require('gulp-htmlmin');
var gulp = require('gulp');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

// elixir(function(mix) {
//     mix.less('app.less');
// });


gulp.task('compress', function() {
    var opts = {
        collapseWhitespace:    	true,
        conservativeCollapse:  	true,
        removeAttributeQuotes: 	true,
        removeComments:        	true,
        removeCommentsFromCDATA:true,
        removeRedundantAttributes:true,
        minifyJS:              	true,
        minifyCSS: 				true,
    };

    return gulp.src('./storage/framework/views/**/*')
               .pipe(htmlmin(opts))
               .pipe(gulp.dest('./storage/framework/views/'));
});