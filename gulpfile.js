var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

var sassPaths = [
    'bower_components/foundation-sites/scss',
    'bower_components/motion-ui/src'
];

var jsPaths = [
    'scripts/**/*.js'
];

var copyPaths = [
    'node_modules/babel-polyfill/dist/polyfill.js',
    'bower_components/angular/angular.js',
    'bower_components/jquery/dist/jquery.js',
    'bower_components/what-input/what-input.js',
    'bower_components/foundation-sites/dist/foundation.js'
];

gulp.task('sass', function() {
    return gulp.src('scss/app.scss')
    .pipe($.sass({
        includePaths: sassPaths
    })
    .on('error', $.sass.logError))
    .pipe($.autoprefixer({
        browsers: ['last 2 versions', 'ie >= 9']
    }))
    .pipe(gulp.dest('www/css'));
});

gulp.task('scripts', function() {
    return gulp.src(jsPaths)
    .pipe($.sourcemaps.init())
    .pipe($.babel())
    .pipe($.concat('app.js'))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest('www/js'));
});

gulp.task('copy-scripts', function() {
    return gulp.src(copyPaths)
    .pipe(gulp.dest('www/js'));
});

gulp.task('default', ['sass','scripts','copy-scripts']);

gulp.task('watch', ['sass', 'scripts'], function() {
    gulp.watch(['scss/**/*.scss'], ['sass']);
    gulp.watch(['scripts/**/*.js'], ['scripts']);
});
