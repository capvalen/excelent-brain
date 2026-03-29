const mix = require('laravel-mix');
const webpack = require('webpack');
/*
mix.browserSync({
    proxy:'http://127.0.0.1:8000'
}); */

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
 mix.setPublicPath('public')

mix.js('resources/js/app.js', 'public/js')
	.postCss('resources/css/app.css', 'public/css', [
			//
	]).vue()
	.version()
.webpackConfig({  // ← Agrega esta configuración
	plugins: [
			new webpack.DefinePlugin({
					'process.env': {
							FACTURACION_TOKEN: JSON.stringify(process.env.FACTURACION_TOKEN)
					}
			})
	]
});
 