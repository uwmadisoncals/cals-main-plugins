var gulp = require('gulp')
var sass = require('gulp-sass')
var uglify = require('gulp-uglify')
var rename = require('gulp-rename')

gulp.task( 'process-sass', done => {

    gulp.src('./src/resources/css/*.sass')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./src/resources/css/'))

    done()
})

gulp.task( 'compress-js', done => {

    gulp.src([
        './src/resources/js/*.js',
        '!./src/resources/js/*.min.js'
    ])
        .pipe(uglify({
            output : {
                comments: true
            }
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest((file) => {
            return file.base;
        }))

    done()
})

gulp.task( 'default', gulp.parallel( 'process-sass', 'compress-js' ) )