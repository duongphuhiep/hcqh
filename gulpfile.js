var gulp = require('gulp');
	gp_riot = require('gulp-riot'),
    gp_concat = require('gulp-concat'),
    gp_rename = require('gulp-rename'),
    gp_uglify = require('gulp-uglify'),
    gp_sourcemaps = require('gulp-sourcemaps'),
    gp_plumber = require('gulp-plumber');

/**
compile all riot tag to gen/**
*/
gulp.task('compile.tags', function(){
    return gulp.src(['app/**/*.rtg'])
        .pipe(gp_riot())
        .pipe(gulp.dest('gen/tags'));
});

/**
compile all riot tag to gen/tags.js
 */
gulp.task('bundle.tags', function(){
    return gulp.src(['app/**/*.rtg'])
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

gulp.task('bundle', ['bundle.tags'], function(){
    return gulp.src(['lib/**/*.js', 'gen/**/*.js', 'app/**/*.js'])
        .pipe(gp_plumber({
            handleError: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(gp_sourcemaps.init())
        .pipe(gp_concat('app.js'))
        .pipe(gulp.dest('dist'))
        .pipe(gp_rename('app.min.js'))

        .pipe(gp_uglify())
        .pipe(gp_sourcemaps.write('./'))
        .pipe(gulp.dest('dist'));
});

gulp.watch('app/**/*', ['bundle']);

gulp.task('default', ['bundle'], function(){});