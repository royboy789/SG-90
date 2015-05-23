var gulp = require('gulp');
var sass = require('gulp-sass');
var concatCSS = require('gulp-concat-css');
var concat = require('gulp-concat');
var watch = require('gulp-watch');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

gulp.task('sass', function(){
	gulp.src('assets/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('build/css'));
});

gulp.task('js', function(){
	
	gulp.src('assets/js/*.js')
		.pipe(concat('js/sg_admin.js'))
		.pipe(uglify())
		.pipe(gulp.dest('build'));
});

gulp.task('boxes_css', function(){
	gulp.src('includes/default_boxes/assets_admin/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('includes/default_boxes/build_admin/css'));
	
	gulp.src('includes/default_boxes/assets_view/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('includes/default_boxes/build_view/css'));
});

gulp.task('plugin_init', function(){
	
	gulp.src('sg90.php')
		.pipe(gulp.dest('plugin_build/'));
	
	gulp.src('build/**/*')
		.pipe(gulp.dest('plugin_build/'));
	
	gulp.src('includes/admin/**/*')
		.pipe(gulp.dest('plugin_build/includes/admin/'));
	
	gulp.src('includes/classes/**/*')
		.pipe(gulp.dest('plugin_build/includes/classes/'));
	
	gulp.src('includes/default_boxes/*.php')
		.pipe(gulp.dest('plugin_build/includes/default_boxes/'));
	
	gulp.src('includes/default_boxes/build_admin/**')
		.pipe(gulp.dest('plugin_build/includes/default_boxes/build_admin/'));
	
	gulp.src('includes/default_boxes/build_view/**')
		.pipe(gulp.dest('plugin_build/includes/default_boxes/build_view'));
	
});


gulp.task('boxes', ['boxes_css']);
gulp.task('default', ['sass', 'js']);