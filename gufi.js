var gulp = require('gulp');
var gp_riot = require('gulp-riot');
var gp_concat = require('gulp-concat');
var gp_rename = require('gulp-rename');
var gp_uglify = require('gulp-uglify');
var gp_sourcemaps = require('gulp-sourcemaps');
var gp_plumber = require('gulp-plumber');
var gp_browserify = require('gulp-browserify');
var liveServer = require("live-server");

/**
compile all riot tag to gen/**
*/
 gulp.task('compile:tag', function(){
     return gulp.src(['app/**/*.tag'])
         .pipe(gp_riot({modular: true}))
         .pipe(gulp.dest('gen'));
 });

/**
compile all riot tag to gen/tags.js
 */
gulp.task('bundle:tag', function(){
    return gulp.src(['app/**/*.tag'])
        .pipe(gp_plumber({
            handleError: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(gp_riot())
        .pipe(gp_concat('tags.js'))
        .pipe(gulp.dest('gen'));
});

// generate dist/*.js with browserify
gulp.task('bundle', ['bundle:tag'], function() {
    // generate dist/main.*.js
    gulp.src('app/main.js')
        .pipe(gp_plumber({
            handleError: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(gp_browserify({
            detectGlobal:true,
            debug : false
        }))
        .pipe(gulp.dest('dist'))

        //minify dist/main.js to dist/main.min.js

        .pipe(gp_sourcemaps.init())
        .pipe(gp_rename('main.min.js'))
        .pipe(gp_uglify())
        .pipe(gp_sourcemaps.write('./'))
        .pipe(gulp.dest('dist'));
});

gulp.task('watch', ['bundle'], function(){
    gulp.watch(['app/**/*', 'lib/**/*', 'backend_mock/**/*'], ['bundle']);
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,gen', open:false});
});

gulp.task('default', ['bundle'], function(){
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,gen'});
});

/*var browserSync = require('browser-sync');
var historyApiFallback = require('connect-history-api-fallback');
gulp.task('reload:admin', function () {
	browserSync.reload();
});
*/
// Watch files for changes & reload
gulp.task('watch:admin', ['bundle:admin'], function () {
	/*browserSync({
		port: 8080,
		notify: false,
		logPrefix: 'HCQH',
		snippetOptions: {
			rule: {
				match: '<span id="browser-sync-binding"></span>',
				fn: function (snippet) {
					return snippet;
				}
			}
		},
		// Run as an https by uncommenting 'https: true'
		// Note: this uses an unsigned certificate which on first access
		//       will present a certificate warning in the browser.
		// https: true,
		server: {
			//baseDir: ['admin'],
			middleware: [ historyApiFallback() ],
			routes: {
				'/bower_components': 'bower_components',
				'/dist': 'dist'
			}
		}
	});*/

	gulp.watch(['admin/**/*', 'backend_mock/**/*', 'lib/**/*'], ['bundle:admin']);
	/*gulp.watch(['dist/admin*'], ['reload:admin']);*/

	//liveServer.start({ignore:'app,admin,lib,backend_mock,tests,reports,gen', open:'/admin'});
});
// generate dist/*.js with browserify
gulp.task('bundle:admin', function() {
	// generate dist/admin.*.js
	gulp.src('admin/admin.js')
		.pipe(gp_plumber({
			handleError: function (err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(gp_browserify({
			detectGlobal:true,
			debug : false
		}))
		.pipe(gulp.dest('dist'))

		//minify dist/admin.js to dist/admin.min.js

		.pipe(gp_sourcemaps.init())
		.pipe(gp_rename('admin.min.js'))
		.pipe(gp_uglify())
		.pipe(gp_sourcemaps.write('./'))
		.pipe(gulp.dest('dist'));
});






var $ = require('gulp-load-plugins')();
var polybuild = require('polybuild');
var runSequence = require('run-sequence');



//vulcanize
/*	var vulcanized = gulp.src(['app/elements/elements.html'])
    	.pipe($.rename('elements.vulcanized.html'))
    	.pipe(gulp.dest('dist'));*/

var optimizeHtmlTask = function (src, dest) {
  var assets = $.useref.assets({searchPath: ['.tmp', 'app', 'dist']});

  return gulp.src(src)
    // Replace path for vulcanized assets
    .pipe($.if('*.html', $.replace('elements/elements.html', 'elements/elements.vulcanized.html')))
    .pipe(assets)
    // Concatenate and minify JavaScript
    .pipe($.if('*.js', $.uglify({preserveComments: 'some'})))
    // Concatenate and minify styles
    // In case you are still using useref build blocks
    .pipe($.if('*.css', $.cssmin()))
    .pipe(assets.restore())
    .pipe($.useref())
    // Minify any HTML
    .pipe($.if('*.html', $.minifyHtml({
      quotes: true,
      empty: true,
      spare: true
    })))
    // Output files
    .pipe(gulp.dest(dest))
    .pipe($.size({title: 'html'}));
};

// Scan your HTML for assets & optimize them
gulp.task('html', function () {
  return optimizeHtmlTask(
    ['app/**/*.html', '!app/{elements,test}/**/*.html'],
    'dist');
});


// Polybuild will take care of inlining HTML imports,
// scripts and CSS for you.
gulp.task('vulcanize', function () {
  return gulp.src('dist/index.html')
    .pipe(polybuild({maximumCrush: true}))
    .pipe(gulp.dest('dist/'));
});

// Rename Polybuild's index.build.html to index.html
gulp.task('rename-index', function () {
  return gulp.src('dist/index.build.html')
    .pipe($.rename('index.html'))
    .pipe(gulp.dest('dist/'));
});

gulp.task('remove-old-build-index', function () {
  return del('dist/index.build.html');
});

// Build production files, the default task
gulp.task('default', ['clean'], function (cb) {
  // Uncomment 'cache-config' after 'rename-index' if you are going to use service workers.
  runSequence(
    ['copy', 'styles'],
    'elements',
    ['jshint', 'images', 'fonts', 'html'],
    'vulcanize','rename-index', 'remove-old-build-index', // 'cache-config',
    cb);
});

//gulp.task('default', ['bundle'], function(){});
