var gulp = require('gulp');
var $    = require('gulp-load-plugins')();
var buffer    = require('vinyl-buffer');
var imagemin = require('gulp-imagemin');
var merge = require('merge-stream');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var spritesmith = require('gulp.spritesmith');


var sassPaths = [
    'bower_components/normalize.scss/sass',
    'bower_components/foundation-sites/scss',
    'bower_components/motion-ui/src'
];


gulp.task('sass', function() {
    return gulp.src('scss/app.scss')
        .pipe($.sass({
            includePaths: sassPaths,
            outputStyle: 'compressed' // if css compressed **file size**
        })
            .on('error', $.sass.logError))
        .pipe($.autoprefixer({
            browsers: ['last 2 versions', 'ie >= 9']
        }))
        .pipe(gulp.dest('css'));
});

gulp.task('default', ['sass'], function() {
    gulp.watch(['scss/**/*.scss'], ['sass']);
});

gulp.task('sprite', function () {
    // Generate our spritesheet
    var spriteData = gulp.src('img/sprites/*.png').pipe(spritesmith({
        imgName: 'sprite.png',
        imgPath: '/img/sprite.png',
        cssName: 'modules/_sprites.scss'
    }));

    // Pipe image stream through image optimizer and onto disk
    var imgStream = spriteData.img
    // DEV: We must buffer our stream into a Buffer for `imagemin`
        .pipe(buffer())
        .pipe(imagemin())
        .pipe(gulp.dest('img'));

    // Pipe CSS stream through CSS optimizer and onto disk
    var cssStream = spriteData.css
    //.pipe(csso())
        .pipe(gulp.dest('scss'));

    // Return a merged stream to handle both `end` events
    return merge(imgStream, cssStream);
});

gulp.task('logo', function () {
    // Generate our spritesheet
    var spriteData = gulp.src('img/logos/*.png').pipe(spritesmith({
        imgName: 'logos.png',
        imgPath: '/img/logos.png',
        cssName: 'modules/_logos.scss'
    }));

    // Pipe image stream through image optimizer and onto disk
    var imgStream = spriteData.img
    // DEV: We must buffer our stream into a Buffer for `imagemin`
        .pipe(buffer())
        .pipe(imagemin())
        .pipe(gulp.dest('img'));

    // Pipe CSS stream through CSS optimizer and onto disk
    var cssStream = spriteData.css
    //.pipe(csso())
        .pipe(gulp.dest('scss'));

    // Return a merged stream to handle both `end` events
    return merge(imgStream, cssStream);
});




gulp.task('compress', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/what-input/dist/what-input.js',
            'bower_components/foundation-sites/dist/js/foundation.js',
            'bower_components/slick-carousel/slick/slick.min.js',
            'js/front/app.js'
        ]
    )
        .pipe(concat('front/min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('js'));
});
