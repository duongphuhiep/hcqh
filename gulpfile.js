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
            debug : true
        }))
        .pipe(gulp.dest('dist'))

        //minify dist/main.js to dist/main.min.js

        .pipe(gp_sourcemaps.init())
        .pipe(gp_rename('main.min.js'))
        .pipe(gp_uglify())
        .pipe(gp_sourcemaps.write('./'))
        .pipe(gulp.dest('dist'));

    // generate dist/fake-backend.js
    //gulp.src('backend_mock/fake-backend.js')
    //    .pipe(gp_plumber({
    //        handleError: function (err) {
    //            console.log(err);
    //            this.emit('end');
    //        }
    //    }))
    //    .pipe(gp_browserify({
    //        detectGlobal:true,
    //        debug : true
    //    }))
    //    .pipe(gulp.dest('dist'));
});

gulp.task('watch', ['bundle'], function(){
    gulp.watch('app/**/*', ['bundle']);
    gulp.watch('lib/**/*', ['bundle']);
    gulp.watch('backend_mock/**/*', ['bundle']);

    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,gen', open:false});
});

gulp.task('default', ['bundle'], function(){
    liveServer.start({ignore:'app,lib,backend_mock,tests,reports,gen'});
});

//gulp.task('default', ['bundle'], function(){});