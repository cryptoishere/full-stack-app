const path = require('path');

const isDev = process.env.NODE_ENV === 'development';
const isProd = !isDev;

module.exports = {
    hot: isDev,
    onListening: function (devServer) {
        if (!devServer) {
            throw new Error('webpack-dev-server is not defined');
        }

        const port = devServer.server.address().port;
        console.log('Listening on port:', port);
        },

        // Specifying a host to use
        host: 'localhost',

        // Specifying a port number
        port: 8989,

        // This is the key to our approach
        // With a backend on http://localhost/PROJECTNAME/
        // we will use this to enable proxying
        proxy: {
        // Star(*) defines all the valid requests
        '*': {
            // Specifying the full path to the public folder
            // Replace "webpack-devserver-php" with your project folder name
            target: `http://localhost/`,
        },
    },

    // Bundle files will be available in the browser under this path
    publicPath: path.resolve(__dirname, 'dist'),

    static: [
        {
            // directory: path.join(__dirname, 'app'),
        },
        {
            // directory: path.join(__dirname, 'css'),
        },
    ]
}