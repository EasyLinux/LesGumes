// webpack.config.js

'use strict';

const path = require( 'path' );

module.exports = {
    // https://webpack.js.org/configuration/entry-context/
    entry: './www/src/app.js',

    // https://webpack.js.org/configuration/output/
    output: {
        path: path.resolve( __dirname, './www/assets/js' ),
        filename: 'bundle.js'
    },
  
    // Useful for debugging.
    devtool: 'source-map',

    // By default webpack logs warnings if the bundle is bigger than 200kb.
    performance: { hints: false }
  }

