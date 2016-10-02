var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var autoprefixer = require('autoprefixer');

module.exports = {
  devtool: 'source-map',
  entry: [
    'webpack-hot-middleware/client?path=http://localhost:3000/__webpack_hmr',
    'bootstrap-loader',
    './src/index',
  ],
  output: {
    path: path.join(__dirname, 'dist'),
    filename: 'bundle.js',
    publicPath: 'http://localhost:3000/static/'
  },
  plugins: [
    new webpack.optimize.OccurenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify("development"),
      'process.env.BABEL_ENV': JSON.stringify("development"),
    }),
    new ExtractTextPlugin('bundle.css', { allChunks: true })
  ],
  module: {
    loaders: [
      { test: /\.js$/,
        loaders: [ 'babel' ],
        exclude: /node_modules/
      }, {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract(
          'style', 'css'
        )
      }, {
        test: /\.scss$/,
        loaders: ['style', 'css', 'postcss', 'sass']
      }, {
        test: /glyphicons-halflings-regular\.woff(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/font-woff"
      }, {
        test: /glyphicons-halflings-regular\.woff2(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/font-woff"
      }, {
        test: /glyphicons-halflings-regular\.ttf(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/octet-stream"
      }, {
        test: /glyphicons-halflings-regular\.eot(\?v=\d+\.\d+\.\d+)?$/,
        loader: "file"
      }, {
        test: /glyphicons-halflings-regular\.svg(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=image/svg+xml"
      }, {
        test: /fontawesome-webfont\.(otf|eot|svg|ttf|woff)\??/,
        loader: "url-loader?limit=8192"
      }, {
        test: /\.(png|jpg|jpeg|gif|svg|woff|woff2)$/, loader: 'url-loader?limit=10000'
      }
    ]
  },

  postcss: [ autoprefixer ],
};
