module.exports = {
    devServer: {
        proxy: {
            '^/users': {
                target: 'http://localhost:5173/',
                ws: true,
                changeOrigin: true
            },
        }
    }
}
