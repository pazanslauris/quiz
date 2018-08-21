module.exports = {
    filenameHashing: false,
    outputDir: 'public/assets',
    devServer: {
        proxy: {
            '/QuizAjax': {
                target: 'http://quiz.test/',
                ws: true,
                changeOrigin: true
            },
            'UserAjax': {
                target: 'http://quiz.test/',
                ws: true,
                changeOrigin: true
            }
        }
    },
    pages: {
        index: {
            entry: 'resources/app.js'
        }
    },
    chainWebpack: config => {
        if (process.env.NODE_ENV === 'production') {
            config.plugins.delete('html-index');
            config.plugins.delete('preload-index');
            config.plugins.delete('prefetch-index');
            config.plugins.delete('copy');
            config.optimization.delete('splitChunks');
        }
    }
};