/**
 * # Main application
 *
 * - the 'gen' package is created by compiling the source files (riot tag or typescript)
 * - the 'dist' package is created by concatenate everything in the 'gen' package
 * - the 'prod' package is created by minify the 'dist' package
 * we deploy only the 'prod' package on the server
 *
 * gulp task will create these packages in the following folders:
 * _gen, _dist, _prod
 *
 * Developper can test both the prod package or dist package with 'watch_prod' task
 *   navigate to http://localhost:8080/
 *   navigate to http://localhost:8080/_prod
 *
 * Developper can test only the dev package with 'watch' task
 *
 * # Admin application
 *
 * The admin app uses Polymer, the polybuild tools do all the job so it is much simpler,
 * polybuild will do everything and export to `gen` package, we have to copy it to the prod package
 * with some small modification.
 *
 * Developper can test the app directly:
 *    navigate to http://localhost:8080/admin
 *
 * Run 'prod:admin' task to create the prod package. To test it:
 *    navigate to http://localhost:8080/_prod/admin
 *
 * @type {Gulp|exports|module.exports}
 */


var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var liveServer = require("live-server");
var runSequence = require('run-sequence');

/**
compile all riot tag to gen/**
*/

gulp.task('_compile_tag', function(){
	 return gulp.src(['app/**/*.tag'])
         .pipe($.riot({modular: true}))
         .pipe(gulp.dest('_gen'));
 });
gulp.task('_copy_appjs', function() {
	return gulp.src(['app/**/*.js'])
		.pipe($.copy('_gen', {prefix:1}));
});
gulp.task('_copy_appjs', function() {
	return gulp.src(['app/**/*.js'])
		.pipe($.copy('_gen', {prefix:1}));
});
gulp.task('_copy_backend_mock', function() {
	return gulp.src(['backend_mock/**/*.js'])
		.pipe($.copy('_gen'));
});

/**
 * The gen folder contains all js which is not bundled
 */
gulp.task('gen', function(cb) {
	runSequence(['_compile_tag', '_copy_appjs', '_copy_backend_mock'], cb);
});

/**
 * generate main.js with browserify
 */
gulp.task('bundle', ['gen'], function() {
	return gulp.src('_gen/main.js')
		.pipe($.plumber({
			handleError: function (err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe($.browserify({
			detectGlobal:true,
			debug : false
		}))
		.pipe(gulp.dest('_dist'));
});

/**
 * The prod folder contains the deployment artifact (the minify version of html/js)
 */
gulp.task('_minify_dist_js', function() {
	return gulp.src(['_dist/*.js'])
		.pipe($.sourcemaps.init())
		//disable fake-backend
		.pipe($.replace('require("./backend_mock/fake-backend")', '//require("./backend_mock/fake-backend")'))
		.pipe($.uglify())
		.pipe($.sourcemaps.write('./'))
		.pipe(gulp.dest('_prod/_dist'));
});
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
	var rootFolder = '../../'; //root to the www folder relative to admin.php, on godaddy we have www/v3.1/backend/admin.php
	return gulp.src(['backend/admin.php'])
		.pipe($.replace('$_SERVER["DOCUMENT_ROOT"]','"'+rootFolder+'"'))
		.pipe(gulp.dest('_prod/backend'));
});
gulp.task('_copy_content_to_prod', function() {
	return 	gulp.src(['content/**/*'])
		.pipe($.copy('_prod'));
});

/**
 * minify everything to the _prod folder, this is the final package to be deployed
 */
gulp.task('prod', ['bundle'], function(cb) {
	runSequence(['_minify_dist_js', '_minify_html_css', '_copy_backend_php'], cb);
});
gulp.task('prod_content', ['prod', '_copy_content_to_prod']);

gulp.task('watch', ['bundle'], function(){
    gulp.watch(['app/**/*', 'lib/**/*', 'backend_mock/**/*'], ['bundle']);
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen', open:false});
});
gulp.task('watch_prod', ['prod_content'], function(){
	gulp.watch(['app/**/*', 'lib/**/*', 'backend_mock/**/*'], ['prod_content']);
	liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen', open:false});
});

gulp.task('default', ['bundle'], function(){
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,_gen'});
});






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
	var rootFolder = "/v3.1/"; //root from the www folder to the website folder, on Godaddy the home folder is www/v3.1
	replaceAdminRootFolder(rootFolder)
});

gulp.task('prod:admin:local', ['gen:admin', '_copy_backend_php','_copy_content_to_prod'], function () {
	var rootFolder = "/hcqh/_prod/";
	replaceAdminRootFolder(rootFolder)
});
gulp.task('watch:admin', ['prod:admin:local'], function () {
	gulp.watch(['admin/**/*', 'backend_mock/**/*', 'lib/**/*'], ['prod:admin:local']);
});



var del = require('del');
gulp.task('clean', function (cb) {
	del(['_gen', '_dist', '_prod'], cb);
});





/*var browserSync = require('browser-sync');
var historyApiFallback = require('connect-history-api-fallback');
gulp.task('reload:admin', function () {
	browserSync.reload();
});
*/
// Watch files for changes & reload
//gulp.task('watch:admin', ['bundle:admin'], function () {
//	/*browserSync({
//		port: 8080,
//		notify: false,
//		logPrefix: 'HCQH',
//		snippetOptions: {
//			rule: {
//				match: '<span id="browser-sync-binding"></span>',
//				fn: function (snippet) {
//					return snippet;
//				}
//			}
//		},
//		// Run as an https by uncommenting 'https: true'
//		// Note: this uses an unsigned certificate which on first access
//		//       will present a certificate warning in the browser.
//		// https: true,
//		server: {
//			//baseDir: ['admin'],
//			middleware: [ historyApiFallback() ],
//			routes: {
//				'/bower_components': 'bower_components',
//				'/dist': 'dist'
//			}
//		}
//	});*/
//
//	gulp.watch(['admin/**/*', 'backend_mock/**/*', 'lib/**/*'], ['bundle:admin']);
//	/*gulp.watch(['dist/admin*'], ['reload:admin']);*/
//
//	//liveServer.start({ignore:'app,admin,lib,backend_mock,tests,reports,gen', open:'/admin'});
//});

// generate dist/*.js with browserify
//gulp.task('bundle:admin', function() {
//	// generate dist/admin.*.js
//	gulp.src('admin/admin.js')
//		.pipe($.plumber({
//			handleError: function (err) {
//				console.log(err);
//				this.emit('end');
//			}
//		}))
//		.pipe($.browserify({
//			detectGlobal:true,
//			debug : false
//		}))
//		.pipe(gulp.dest('dist'))
//
//		//minify dist/admin.js to dist/admin.min.js
//
//		.pipe($.sourcemaps.init())
//		.pipe($.rename('admin.min.js'))
//		.pipe($.uglify())
//		.pipe($.sourcemaps.write('./'))
//		.pipe(gulp.dest('dist'));
//});
//

