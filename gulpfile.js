/**
 * Compile everything by running the following gulp task:
 *
 * gen:admin
 * prod:admin
 * prod
 *
 * It will generate
 *
 * _dist is used for http://localhost/hcqh
 * _prod is used to deploy on Godaddy
 *
 * Run the 'clean' task to remove these folders
 */


var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var liveServer = require("live-server");
var runSequence = require('run-sequence');
var webpack = require('webpack-stream');

/**
 * generate _dist/main.js and _prod/_dist/main.js
 */
gulp.task('bundle', function() {
	return gulp.src('./app/main.js')
		.pipe(webpack(require('./webpack.config.js')))
		.pipe(gulp.dest('_dist/'))
		.pipe(gulp.dest('_prod/_dist/'));
});

/**
 * The prod folder contains the deployment artifact (the minify version of html/js)
 */
gulp.task('_minify_html_css', function() {
	return gulp.src(['*.html', '*.css', 'favicon.ico'])
		.pipe($.if('*.html', $.minifyHtml({
					quotes: true,
					empty: true,
					spare: true
				})))
		.pipe($.if('*.css', $.cssmin()))
		.pipe(gulp.dest('_prod'));
});
gulp.task('_copy_backend_php', function() {
	return gulp.src(['backend/**/*'])
		.pipe($.copy('_prod'));
});
gulp.task('_copy_backend_php_prod', ['_copy_backend_php'], function() {
	//TODO: change after publish the app to the root folder on GoDaddy
	var rootFolder = '../'; //root to the www folder relative to admin.php, on godaddy we have www/v3.1/backend/admin.php
	return gulp.src(['backend/*.php'])
		.pipe($.replace('$_SERVER["DOCUMENT_ROOT"]','"'+rootFolder+'"'))
		.pipe($.replace('define("ROOT_DIR", "../../")','define("ROOT_DIR", "'+rootFolder+'")'))
		.pipe(gulp.dest('_prod/backend'));
});
//gulp.task('_copy_content_to_prod', function() {
//	return 	gulp.src(['content/**/*'])
//		.pipe($.copy('_prod'));
//});

/**
 * minify everything to the _prod folder, this is the final package to be deployed
 */
gulp.task('prod', ['bundle'], function(cb) {
	runSequence(['_minify_html_css', '_copy_backend_php_prod'], cb);
});
//gulp.task('prod_content', ['prod', '_copy_content_to_prod']);

gulp.task('watch', ['bundle'], function(){
    gulp.watch(['app/**/*', 'lib/**/*', 'backend_mock/**/*'], ['bundle']);
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen', open:false});
});
//gulp.task('watch_prod', ['prod'], function(){
//	gulp.watch(['app/**/*', 'lib/**/*', 'backend_mock/**/*'], ['prod_content']);
//	liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen', open:false});
//});





// Polybuild will take care of inlining HTML imports,
// scripts and CSS for you.
var polybuild = require('polybuild');

gulp.task('gen:admin', function () {
	gulp.src('admin/index.html')
		.pipe(polybuild({maximumCrush: true}))
		.pipe(gulp.dest('_gen/admin'));

	//rename it to prod
	gulp.src('_gen/admin/index.build.html')
		.pipe($.rename('index.html'))
		.pipe(gulp.dest('_prod/admin'));
});

function replaceAdminRootFolder(rootFolder) {
	gulp.src('_gen/admin/index.build.js')
		.pipe($.replace('page.rootFolder="/hcqh/"', 'page.rootFolder="'+rootFolder+'"'))
		.pipe(gulp.dest('_prod/admin'));
}

gulp.task('prod:admin', ['gen:admin', '_copy_backend_php_prod'], function () {
	//TODO: change after publish the app to the root folder on GoDaddy
	var rootFolder = "/"; //root from the www folder to the website folder, on Godaddy the home folder is www/v3.1
	replaceAdminRootFolder(rootFolder)
});

//gulp.task('prod:admin:local', ['gen:admin', '_copy_backend_php','_copy_content_to_prod'], function () {
//	var rootFolder = "/hcqh/_prod/";
//	replaceAdminRootFolder(rootFolder)
//});
//gulp.task('watch:admin', ['prod:admin'], function () {
//	gulp.watch(['admin/**/*', 'backend_mock/**/*', 'lib/**/*'], ['prod:admin:local']);
//});


var del = require('del');
gulp.task('clean', function (cb) {
	del(['_gen', '_dist', '_prod'], cb);
});


gulp.task('default', ['clean', 'gen:admin', 'prod', 'prod:admin'], function(){
	liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen'});
});
