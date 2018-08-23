import Api from '../api.js';

class QuizRepository {
    constructor() {
        this.quizApi = new Api('QuizAjax');
    }

    getAllQuizzes() {
        return this.quizApi.getResponseAsync('getQuizzes');
    }

    startQuiz(quizId) {
        return this.quizApi.getResponseAsync('startQuiz', {quizId});
    }

    submitAnswer(answerId) {
        return this.quizApi.getResponseAsync('submitAndLoadNextQuestion', {answerId});
    }
}

export default new QuizRepository();