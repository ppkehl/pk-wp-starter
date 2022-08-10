'use strict'

//################################################################################################################
//
//   THEME ESPECIFIC CONSTANTS
//
//################################################################################################################

//===============================================================================================================
// GENERAL INFOS
//===============================================================================================================

//--------------------------------------------------------------------------
// Theme Infos
//--------------------------------------------------------------------------

const themeName = 'Base Theme'
const themeURI = ''
const themeDescription = 'Base Theme'
const themeAuthor = 'Pedro Kehl'
const themeAuthorURL = 'https://pedrokehl.net'
const themeVersion = '1.0'
const themeFolderName = 'base_theme'

//--------------------------------------------------------------------------
// Server Infos
//--------------------------------------------------------------------------

const serverFolderName = 'wpbase'
const serverPort = 8000

//===============================================================================================================
// PATHS
//===============================================================================================================

//--------------------------------------------------------------------------
// Main Paths
//--------------------------------------------------------------------------
const srcPath = 'src/'
const tmpPath = 'tmp/'
const distPath = '../../../../xampp/htdocs/' + serverFolderName + '/wp-content/themes/' + themeFolderName + '/'
//const distPath = 'dist/'

//--------------------------------------------------------------------------
// FTP Configuration
//--------------------------------------------------------------------------
const FTPPath = '/' + serverFolderName + '/wp-content/themes/' + themeFolderName + '/'
const FTPHost = "185.211.7.221"
const FTPUser = "u341779492.remote"
const FPTPass = "hdE*}{sSCFh7"

//################################################################################################################
//
//   FOLDER/FILE PATHS (Only needed to be updated if folder structure is changed)
//
//################################################################################################################

//===============================================================================================================
// PATHS
//===============================================================================================================

//--------------------------------------------------------------------------
// Tokens
//--------------------------------------------------------------------------
const tokensWatchPath = [
	srcPath + 'assets/tokens/**/*.json', 
	'!' + srcPath + 'assets/tokens/compiled.json'
]

//--------------------------------------------------------------------------
// Javascript
//--------------------------------------------------------------------------
const jsInputPath = srcPath + 'assets/js/scripts.js'

//--------------------------------------------------------------------------
// SCSS
//--------------------------------------------------------------------------
const cssInputPath = [
	distPath + 'assets/js/*.css',
	srcPath + 'assets/scss/**/*.scss',
	'!' + srcPath + 'assets/scss/cms/*.scss',
	'!' + srcPath + 'assets/scss/utility/*.scss',
	'!' + srcPath + 'assets/scss/abstracts/_tokens.scss'
]
const cssWatchPath = [
	srcPath + 'assets/scss/**/*.scss',
	'!' + srcPath + 'assets/scss/cms/*.scss',
	'!' + srcPath + 'assets/scss/utility/*.scss',
	'!' + srcPath + 'assets/scss/abstracts/_tokens.scss'
]
const cssUtilityInputPath = srcPath + 'assets/scss/utility/**/**/*.scss'
const cssCmsInputPath = srcPath + 'assets/scss/cms/*.scss'

//--------------------------------------------------------------------------
// Files
//--------------------------------------------------------------------------
const filesInputPath = [
	srcPath + 'template-parts/**/*.*',
	srcPath + 'page-templates/*.*',
	srcPath + 'functions/**/**/*.*',
	srcPath + '*.{php,html,png,js}',
	srcPath + 'public/**/*.*',
	'!' + srcPath + 'public/imgs/*.*',
	'!' + srcPath + 'public/icons/*.*'
]

//--------------------------------------------------------------------------
// Images
//--------------------------------------------------------------------------
const bmpsInputPath = [srcPath + 'public/imgs/*.{png,gif,jpg,jpeg,bmp}',srcPath + 'public/icons/*.{png,gif,jpg,jpeg,bmp}']
const vectorsInputPath = [srcPath + 'public/imgs/*.svg',srcPath + 'public/icons/*.svg']

//################################################################################################################
//
//   GULP FUNCTIONS (Don't change)
//
//################################################################################################################

//===============================================================================================================
// PLUGINS
//===============================================================================================================

//--------------------------------------------------------------------------
// Main
//--------------------------------------------------------------------------
const gulp = require('gulp')
const series = require('gulp')
const fs = require("fs")

//--------------------------------------------------------------------------
// Sass
//--------------------------------------------------------------------------
const sass = require('gulp-sass')(require('sass'))
const autoprefixer = require('gulp-autoprefixer')
const concat = require('gulp-concat')
const cleanCSS = require('gulp-clean-css')
const purgecss = require('gulp-purgecss')
const StyleDictionary = require('style-dictionary')

//--------------------------------------------------------------------------
// Misc
//--------------------------------------------------------------------------
const inject = require('gulp-inject-string')
const del = require('del')
const dateformat = require('date-format')
const mergeJSON = require('gulp-merge-json')
const googleWebFonts = require('gulp-google-webfonts')
const rev = require('gulp-rev')

//--------------------------------------------------------------------------
// JS
//--------------------------------------------------------------------------
const rollup = require('rollup')
const terser = require('rollup-plugin-terser').terser
const commonjs = require('@rollup/plugin-commonjs')
const nodeResolve = require('@rollup/plugin-node-resolve').nodeResolve
const importCss = require("rollup-plugin-import-css")
const babel = require('gulp-babel')

//--------------------------------------------------------------------------
// Images
//--------------------------------------------------------------------------
const gulpSquoosh = require("gulp-squoosh")
const svgmin = require("gulp-svgmin")

//--------------------------------------------------------------------------
// Browser sync
//--------------------------------------------------------------------------
const browserSync = require('browser-sync').create()

//--------------------------------------------------------------------------
// FTP
//--------------------------------------------------------------------------
const vftp = require("vinyl-ftp")

//===============================================================================================================
// TASKS
//===============================================================================================================

//--------------------------------------------------------------------------
// Get Timestamp
//--------------------------------------------------------------------------
function getTimestamp() {
	return '\/*\n------------------------------------\n \
	Do not edit directly \n Generated: ' +
		dateformat('dd/MM/yyyy hh:mm:ss.SSS', new Date()) +
		'\n------------------------------------\n*/ \n \n';
}

//--------------------------------------------------------------------------
// Browswer Sync
//--------------------------------------------------------------------------
gulp.task('browser-sync:create', (cb) => {
	browserSync.init({
		baseDir: '.',
		proxy: "localhost/" + serverFolderName,
		port: serverPort
	})
	cb()
})

gulp.task('browser-sync:reload', (cb) => {
	browserSync.reload()
	cb()
})

//--------------------------------------------------------------------------
// Delete dist directory
//--------------------------------------------------------------------------
gulp.task("del-dist", () => {
	return del(distPath + '**', { force: true })
})

//--------------------------------------------------------------------------
// Download Google Fonts
//--------------------------------------------------------------------------
gulp.task('google-fonts', () => {
	return gulp.src('./fonts.list')
		.pipe(googleWebFonts({
			fontsDir: '../../public/fonts/',
			cssDir: '../../compiled-assets/css/',
			cssFilename: 'fonts.css'
		}))
		.pipe(gulp.dest(srcPath + '/public/fonts/'))
});

//--------------------------------------------------------------------------
// Process javascript
//--------------------------------------------------------------------------
gulp.task('javascript', () => {
	return rollup
		.rollup({
			input: jsInputPath,
			plugins: [
				babel({
					presets: [
						["es2015", {
							"modules": true
						}]
					],
					sourceMaps: true,
					babelrc: false,
					exclude: 'node_modules/**'
				}),
				commonjs(),
				importCss(),
				nodeResolve(),
				terser({ format: { comments: false } })
			],
			cache: true,
		})
		.then(bundle => {
			return bundle.write(
				{
					file: srcPath + 'compiled-assets/js/scripts.min.js',
					format: 'cjs',
					name: 'scripts',
					sourcemap: true,
				}
			);
		})
});

//--------------------------------------------------------------------------
// Process Tokens
//--------------------------------------------------------------------------
gulp.task('tokens:mergeJSON', () => {
	return gulp.src([srcPath + 'assets/tokens/**/*.json', '!' + srcPath + 'assets/tokens/compiled.json'])
		.pipe(mergeJSON({
			transform: (mergedJson) => {
				return {
					'global': {
						...mergedJson,
					}
				}
			},
			fileName: 'compiled.json'
		}))
		.pipe(gulp.dest(srcPath + 'assets/tokens/'))
})

gulp.task('tokens:proccess', async () => {
	StyleDictionary.registerTransform({
		name: 'utility/name',
		type: 'name',
		transformer: token => {
			const cleanNames = string => string
				.replace('/', '-slash-')
				.replace('.', '-dot-')
				.toLowerCase();
			return cleanNames(token.path.join("-"));
		},
	})
	StyleDictionary.registerTransform({
		name: 'utility/ratio',
		type: 'value',
		matcher: token => {
			return token.attributes.category === 'ratio'
		},
		transformer: token => {
			var ratio = token.value.split("x");
			var width = ratio[0];
			var height = ratio[1];
			return height / width * 100 + "%";
		},
	})
	StyleDictionary.registerTransformGroup({
		name: 'utilities',
		transforms: [
			'attribute/cti',
			'utility/name',
			'utility/ratio',
			'time/seconds',
			'content/icon',
			'color/hex8'
		]
	});
	const utilitiesStyleDictionary = StyleDictionary
		.extend({
			source: [srcPath + "assets/tokens/compiled.json"],
			parsers: [{
				pattern: /\.json$/,
				// Remove JSON wrapper key, if any
				parse: ({ contents }) => {
					let jsonObj = JSON.parse(contents);
					if (Object.keys(jsonObj).length > 0) {
						return jsonObj[Object.keys(jsonObj)[0]];
					} else {
						return contents;
					}
				}
			}],
			platforms: {
				scss: {
					transformGroup: "utilities",
					buildPath: srcPath + "assets/scss/abstracts/",
					files: [{
						destination: "_tokens.scss",
						filter: function (token) {
							if (Object.prototype.toString.call(token.value) != '[object Object]') {
								return token;
							}
						},
						format: "scss/map-deep",
						options: {
							showFileHeader: false,
							outputReferences: true
						}
					}]
				}
			}
		})
	return utilitiesStyleDictionary.buildAllPlatforms()
});

gulp.task("tokens", gulp.series("tokens:mergeJSON", "tokens:proccess"))

//--------------------------------------------------------------------------
// Process CSS, concat and minify
//--------------------------------------------------------------------------
gulp.task('styles:general', () => {
	return gulp
		.src(cssInputPath)
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({ cascade: false }))
		.pipe(concat('theme.css'))
		.pipe(inject.prepend('/* purgecss start ignore */\n'))
		.pipe(inject.prepend(getTimestamp()))
		.pipe(inject.append('\n/* purgecss end ignore */'))
		.pipe(gulp.dest(srcPath + 'compiled-assets/css/'))
})

gulp.task('styles:utilities', () => {
	return gulp
		.src(cssUtilityInputPath)
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({ cascade: false }))
		.pipe(concat('utility.css'))
		.pipe(inject.prepend(getTimestamp()))
		.pipe(gulp.dest(srcPath + 'compiled-assets/css/'))
})

gulp.task("styles:cms", () => {
	return gulp
		.src(cssCmsInputPath)
		.pipe(sass().on('error', sass.logError))
		.pipe(concat('cms.css'))
		.pipe(inject.prepend(getTimestamp()))
		.pipe(gulp.dest(srcPath + 'compiled-assets/css/'))
})

gulp.task('styles:bundle-dev', () => {
	return gulp
		.src(srcPath + 'assets/css/*.css')
		.pipe(gulp.dest(distPath + 'assets/css/'))
})

gulp.task('styles:bundle-dist', () => {
	return gulp
		.src([srcPath + 'assets/css/fonts.css', srcPath + 'assets/css/theme.css', srcPath + 'assets/css/utility.css'], { "allowEmpty": true })
		.pipe(concat('styles.min.css'))
		.pipe(purgecss({
			content: [srcPath + '**/**/*.php'],
			extractors: [{
				extractor: (content) => {
					// fix for escaped prefixes (sm:, lg:, etc)
					return content.match(/[\w-:./]+(?<!:)/g) || []
				},
				extensions: ['css', 'php'],
			}],
		}))
		.pipe(cleanCSS())
		.pipe(inject.prepend(getTimestamp()))
		.pipe(gulp.dest(distPath + 'assets/css/'))
})

gulp.task("styles:wp-style", (cb) => {
	const stylecss = fs.writeFile(distPath + 'style.css', '', (err) => {
		if (err) throw err;
		return gulp
		.src(distPath + 'style.css')
		.pipe(inject.append('/*'))
		.pipe(inject.append('\nTheme Name: ' + themeName))
		.pipe(inject.append('\nTheme URI: ' + themeURI))
		.pipe(inject.append('\nDescription: ' + themeDescription))
		.pipe(inject.append('\nAuthor: ' + themeAuthor))
		.pipe(inject.append('\nAuthor URL: ' + themeAuthorURL))
		.pipe(inject.append('\nVersion: ' + themeVersion))
		.pipe(inject.append('\nGenerated at: ' + dateformat('dd/MM/yyyy hh:mm:ss.SSS', new Date())))
		.pipe(inject.append('\n\n--- Do not edit directly ---'))
		.pipe(inject.append('\n\n*/'))
		.pipe(gulp.dest(distPath))
	});
	cb()
})

gulp.task("styles:dev", gulp.series("styles:general", "styles:utilities", "styles:bundle-dev", "styles:cms", "styles:wp-style", ))
gulp.task("styles:dist", gulp.series("styles:general", "styles:utilities", "styles:bundle-dist", "styles:cms", "styles:wp-style"))

//--------------------------------------------------------------------------
// Copy files to dist
//--------------------------------------------------------------------------
gulp.task("files", () => {
	return gulp
		.src(filesInputPath, { base: srcPath })
		.pipe(gulp.dest(distPath))
})

//--------------------------------------------------------------------------
// Proccess images to dist
//--------------------------------------------------------------------------
gulp.task("images:bitmaps", () => {
	return gulp
		.src(bmpsInputPath, { base: srcPath + 'public/' })
		.pipe(gulpSquoosh(({
			encodeOptions: {
				webp: {},
				mozjpeg: {},
				oxipng: {}
			},
		})))
		.pipe(gulp.dest(distPath + 'public/'))
})

gulp.task("images:vectors", () => {
	return gulp
		.src(vectorsInputPath, { base: srcPath + 'public/' })
		.pipe(svgmin({
			multipass: true,
			full: true,
			plugins: [
				'removeDoctype',
				'removeComments',
				'sortAttrs',
			],
		}))
		.pipe(gulp.dest(distPath + 'public/'))
})

gulp.task("images", gulp.series("images:bitmaps", "images:vectors"))

//--------------------------------------------------------------------------
// Send to FTP
//--------------------------------------------------------------------------
gulp.task('ftp', () => {
	var conn = vftp.create({
		host: FTPHost,
		user: FTPUser,
		pass: FPTPass,
		parallel: 1
	})
	return gulp.src(distPath + '**', { base: './' + distPath, buffer: false })
		.pipe(conn.newer(FTPPath + '/')) // only upload newer files
		.pipe(conn.dest(FTPPath + '/'))
})

//--------------------------------------------------------------------------
// Generate revision/manifest
//--------------------------------------------------------------------------
gulp.task("manifest:cleanup", () => {
	return del([distPath + 'assets/css', distPath + 'assets/js'], { force: true })
})

gulp.task("manifest", () => {
	return gulp
		.src(srcPath + 'compiled-assets/**/*.*', { base: srcPath + 'compiled-assets/'})
		.pipe(gulp.dest(distPath + 'assets/'))
		.pipe(rev())
		.pipe(gulp.dest(distPath + 'assets/'))
		.pipe(rev.manifest({
			path: distPath + 'manifest.json',
			base: distPath + 'assets/',
		}))
		.pipe(gulp.dest(distPath + 'assets/'));
})

//--------------------------------------------------------------------------
// Inject logo
//--------------------------------------------------------------------------
gulp.task("inject-logo", () => {
	let logoFile = fs.readFileSync("logo.txt", "utf8");
	return gulp
		.src(srcPath + 'header.php')
		.pipe(inject.prepend('\n<!--\n' + logoFile + '\n-->\n'))
		.pipe(gulp.dest(distPath));
})

//--------------------------------------------------------------------------
// Build function
//--------------------------------------------------------------------------
gulp.task("build", gulp.series("del-dist", "google-fonts", "files", "javascript", "images", "tokens", "styles:dist", "manifest", "inject-logo"))

//--------------------------------------------------------------------------
// Build and deploy function
//--------------------------------------------------------------------------
gulp.task("deploy", gulp.series("build", "ftp"))

//--------------------------------------------------------------------------
// Watch function
//--------------------------------------------------------------------------
gulp.task('watch', () => {
	gulp.watch(filesInputPath, gulp.series("files", "browser-sync:reload"))
	gulp.watch(bmpsInputPath, gulp.series("images:bitmaps", "browser-sync:reload"))
	gulp.watch(vectorsInputPath, gulp.series("images:vectors", "browser-sync:reload"))
	gulp.watch(tokensWatchPath, gulp.series("tokens", "styles:utilities", "styles:general", "browser-sync:reload"))
	gulp.watch(cssWatchPath, gulp.series("styles:general", "manifest:cleanup", "manifest", "browser-sync:reload"))
	gulp.watch(cssCmsInputPath, gulp.series("styles:cms", "manifest:cleanup", "manifest", "browser-sync:reload"))
	gulp.watch(cssUtilityInputPath, gulp.series("styles:general", "styles:utilities", "browser-sync:reload"))
	gulp.watch(srcPath + 'assets/js/**/*.js', gulp.series('javascript', "manifest:cleanup", "manifest", "browser-sync:reload"))
})

//--------------------------------------------------------------------------
// Default function
//--------------------------------------------------------------------------
gulp.task("default", gulp.series("del-dist", "files", "javascript", "images", "tokens", "styles:dev", "manifest:cleanup", "manifest", "browser-sync:create", "watch"))