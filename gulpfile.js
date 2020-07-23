// Config
const config = {
	source: 'assets/sass',
	dest: 'assets/css'
};

// Modules
const gulp = require('gulp');
const sass = require('gulp-sass');
const sassglob = require('gulp-sass-glob');
const minifycss = require('gulp-cssnano');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const postcssLogical = require('postcss-logical');

sass.compiler = require('sass');

/**
 * Default Task that runs when you type gulp in the console
 */
const defaultTask = function (done) {
	gulp.series('compileSass', 'watch');
	done();
};

/**
 * Compile SASS
 *
 * Compiles your SASS files in to one stylesheet
 */
const compileSass = function () {
	return (
		gulp
			.src([`${config.source}/*.scss`, `${config.source}/**/*.scss`])

			// Compile SASS
			.pipe(sassglob())
			.pipe(
				sass({
					// outputStyle: `expanded`,
					includePaths: [`${config.source}`]
				}).on('error', sass.logError)
			)
			.pipe(plumber())

			// PostCSS & Minify
			.pipe(postcss([postcssLogical()]))
			.pipe(minifycss())

			// Save it and update the browser
			.pipe(gulp.dest(config.dest))
	);
};

function watch(done) {
	// Watch .scss files
	gulp.watch(
		[
			`${config.source}/*.scss`,
			`${config.source}/**/*.scss`,
			`${config.source}/**/**/*.scss`
		],
		compileSass
	);

	done();
}

const watchAndSync = gulp.parallel(watch);

exports.default = defaultTask;
exports.compileSass = compileSass;
exports.watch = watchAndSync;
