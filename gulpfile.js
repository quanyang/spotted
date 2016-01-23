var gulp = require('gulp'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache'),
    minifycss = require('gulp-minify-css'),
    sass = require('gulp-sass'),

    input = {
      'images': 'app/src/images/**/*',
      'stylesheets': 'app/src/stylesheets/**/*',
      'javascript': 'app/src/javascript/**/*.js'
    },

    output = {
      'images': 'app/assets/img',
      'stylesheets': 'app/assets/stylesheets',
      'javascript': 'app/assets/javascript'
    };

gulp.task('build', ['build-css', 'build-js', 'compress-images']);

gulp.task('default', function(){
  gulp.watch(input.stylesheets, ['build-css']);
  gulp.watch(input.javascript, ['build-js']);
  gulp.watch(input.images, ['compress-images']);
});

gulp.task('compress-images', function(){
  gulp.src(input.images)
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest(output.images));
});

gulp.task('build-css', function(){
  gulp.src(input.stylesheets)
    .pipe(sass())
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest(output.stylesheets))
});

gulp.task('build-js', function(){
  gulp.src(input.javascript)
    .pipe(concat('main.js'))
    .pipe(gulp.dest(output.javascript))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest(output.javascript))
});
