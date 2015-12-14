var gulp = require('gulp'),
    tasks = require('gulp-load-plugins')(),
    concat = require('gulp-concat'),
    rimraf = require('rimraf'),
    bootstrapPath = 'bower_components/bootstrap/dist/',
    jqueryPath = 'bower_components/jquery/dist/',
    commonPath = 'vendor/nicolasdewez/webhome-common/Resources/public/scripts/dist/',
    srcPath = 'app/Resources/public/',
    cssPath = srcPath + 'stylesheets/',
    cssFile = cssPath + 'app.less',
    jsPath = srcPath + 'scripts/',
    bootstrapJsFile = bootstrapPath + 'js/bootstrap.js',
    jqueryJsFile = jqueryPath + 'jquery.js',
    commonJsFile = commonPath + 'common.js',
    jsFile = jsPath + '*',
    imgPath = srcPath + 'images/*',
    fontPath = bootstrapPath + 'fonts/*',
    distPath = 'web/',
    distJsCssPath = distPath + 'public/',
    distImgPath = distPath + 'img/',
    distFontPath = distPath + 'fonts/';

gulp.task('clean', function(callback) {
    rimraf.sync(distJsCssPath);
    rimraf.sync(distImgPath);
    rimraf.sync(distFontPath);

    callback();
});

gulp.task('css', function() {
    gulp.src(cssFile)
        .pipe(tasks.plumber())
        .pipe(tasks.less())
        .pipe(tasks.autoprefixer())
        .pipe(gulp.dest(distJsCssPath));
});

gulp.task('cssDist', function() {
    gulp.src(cssFile)
        .pipe(tasks.plumber())
        .pipe(tasks.less())
        .pipe(tasks.autoprefixer())
        .pipe(tasks.csso())
        .pipe(gulp.dest(distJsCssPath));
});

gulp.task('js', function() {
    gulp.src([jqueryJsFile, bootstrapJsFile, commonJsFile, jsFile])
        .pipe(concat('app.js'))
        .pipe(tasks.plumber('app.js'))
        .pipe(gulp.dest(distJsCssPath));
});

gulp.task('jsDist', function() {
    gulp.src([jqueryJsFile, bootstrapJsFile, commonJsFile, jsFile])
        .pipe(concat('app.js'))
        .pipe(tasks.plumber())
        .pipe(tasks.uglify())
        .pipe(gulp.dest(distJsCssPath));
});

gulp.task('img', function() {
    gulp.src(imgPath)
        .pipe(gulp.dest(distImgPath));
});

gulp.task('fonts', function() {
    gulp.src(fontPath)
        .pipe(gulp.dest(distFontPath));
});

gulp.task('livereload', function() {
    tasks.livereload.listen();

    gulp.watch(cssPath + '**/*.less', ['css']);
    gulp.watch(jsPath + '**/*.js', ['js']);
});

gulp.task('default', ['clean', 'css', 'js', 'img', 'fonts', 'livereload']);
gulp.task('dist', ['clean', 'cssDist', 'jsDist', 'img', 'fonts']);
