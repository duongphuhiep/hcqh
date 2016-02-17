var webpack = require('webpack');

// postcss plugins
//var cssimport = require('postcss-import');
//var customProperties = require('postcss-custom-properties');
//var autoprefixer = require('autoprefixer-core');
//var csswring = require('csswring');
//var cssnested = require('postcss-nested');

module.exports = {
	entry: {
		app : ['./app/main.js']
	},
	output: {
		path: __dirname + '/_dist/',
		filename: 'main.js'
	},
	//devtool: 'eval',
	//debug: true,
	plugins: [
		new webpack.ProvidePlugin({
			riot: 'riot'
		}),
		//new webpack.optimize.UglifyJsPlugin()
	],
	module: {
		loaders: [
			{ test: /\.tag$/, exclude: /node_modules/, loader: 'riotjs' }
		]
	},
	externals: {
		'commonmark': 'commonmark',
		'moment': 'moment',
		'jquery': 'jQuery'
	}

	//module: {
	//	preLoaders: [
	//		{ test: /\.tag$/, exclude: /node_modules/, loader: 'riotjs-loader', query: { type: 'es6' } }
	//	],
	//	loaders: [
	//		{ test: /\.js|\.tag$/, exclude: /node_modules/, include: /app/, loader: 'babel-loader', query: {modules: 'common'} },
	//		{ test: /\.css$/, loader: 'style-loader!css-loader!postcss-loader' }
	//	]
	//},
	//postcss: [cssimport, cssnested, customProperties, autoprefixer, csswring],
	//devServer: {
	//	contentBase: './',
	//	port: 1337,
	//	hot: true,
	//	inline: true
	//}
};
