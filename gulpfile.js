'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sassLint = require('gulp-sass-lint');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var babel = require('gulp-babel');

gulp.task('babel', function() {
    return gulp.src('app/Resources/client/jsx/project/details/*.jsx')
        .pipe(babel({
            presets: ['es2015', 'react']
        }))
        .pipe(concat('details.js'))
        .pipe(gulp.dest('web/assets/js/project'));
});

gulp.task('sass', function () {
  return gulp.src('web/assets/scss/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sourcemaps.write('.', {includeContent: false}))
    .pipe(gulp.dest('web/assets/css'));
});

gulp.task('sassLint', function() {
  gulp.src('web/assets/scss/*.s+(a|c)ss')
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
});

gulp.task('css', ['sassLint','sass'], function () {
});

gulp.task('default', ['css'], function() {
  // place code for your default task here
});

