'use strict'; // eslint-disable-line

const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  mode: "production",
  entry: {
    "main": ["includes/css/styles.scss"],
    "vendor": [
      'jquery'
    ],
  },
  output: {
    path: path.resolve(__dirname, "dist"), // string
    filename: "bundle.js",
    publicPath: "/includes/css/",
  },
  module: {
    rules: [{
      test: /\.scss$/,
      include: path.resolve(__dirname, "includes/css/"),
      use: [
        MiniCssExtractPlugin.loader,
        'css-loader',
        'postcss-loader',
        'sass-loader',
      ],
    }]
  },
  resolve: {
    modules: [
      __dirname,
      path.resolve(__dirname, "images"),
      "node_modules",
    ],
    enforceExtension: false,
  },
  devtool: "source-map",
  externals: {
    jquery: 'jQuery',
  },
  stats: "errors-only",  // lets you precisely control what bundle information gets displayed
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
      chunkFilename: '[id].css',
    })
  ],
};
