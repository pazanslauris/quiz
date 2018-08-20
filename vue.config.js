module.exports = {
    devServer: {
        proxy: {
            '/QuizAjax': {
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
    }
};