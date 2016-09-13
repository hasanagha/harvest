var gulp          = require('gulp'),
    plumber       = require('gulp-plumber'),
    rename        = require('gulp-rename'),
    autoprefixer  = require('gulp-autoprefixer'),
    sass          = require('gulp-sass'),
    browserSync   = require('browser-sync'),
    notify        = require('gulp-notify');


gulp.task('browser-sync', function() {
  browserSync({
    proxy: "http://localhost:8888/d6/",
  });
});


gulp.task('bs-reload', function () {
  browserSync.reload();
});


gulp.task('sass-to-css', function(){
  gulp.src(['../sass/**/*.scss'])
    .pipe(plumber({
      errorHandler: notify.onError("Error: <%= error.message %>")
    }))
    .pipe(sass())
    .pipe(autoprefixer({browsers: ['last 2 versions']}))
    .pipe(gulp.dest('../css/'))
    .pipe(browserSync.reload({stream:true}))
});


gulp.task('default', ['browser-sync'], function(){
  gulp.watch("../sass/**/*.scss", ['sass-to-css']);
});

gulp.task('watch-sass', [], function(){
  gulp.watch("../sass/**/*.scss", ['sass-to-css']);
});
