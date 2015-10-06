var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var liveServer = require("live-server");

/**
compile all riot tag to gen/**
*/
 gulp.task('compile:tag', function(){
     return gulp.src(['app/**/*.tag'])
         .pipe($.riot({modular: true}))
         .pipe(gulp.dest('gen'));
 });

/**
compile all riot tag to gen/tags.js
 */
gulp.task('bundle:tag', function(){
    return gulp.src(['app/**/*.tag'])
        .pipe($.plumber({
            handleError: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe($.riot())
        .pipe($.concat('tags.js'))
        .pipe(gulp.dest('gen'));
});

// generate dist/*.js with browserify
gulp.task('bundle', ['bundle:tag'], function() {
    // generate dist/main.*.js
    gulp.src('app/main.js')
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
        .pipe(gulp.dest('dist'))

        //minify dist/main.js to dist/main.min.js

        .pipe($.sourcemaps.init())
        .pipe($.rename('main.min.js'))
        .pipe($.uglify())
        .pipe($.sourcemaps.write('./'))
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
		.pipe(gulp.dest('dist'))

		//minify dist/admin.js to dist/admin.min.js

		.pipe($.sourcemaps.init())
		.pipe($.rename('admin.min.js'))
		.pipe($.uglify())
		.pipe($.sourcemaps.write('./'))
		.pipe(gulp.dest('dist'));
});

// Polybuild will take care of inlining HTML imports,
// scripts and CSS for you.
var polybuild = require('polybuild');

gulp.task('vulcanize', function () {
  return gulp.src('admin/index.html')
    .pipe(polybuild({maximumCrush: true}))
    .pipe(gulp.dest('admin'));
});
