import Api from '../api.js';

class AdminRepository {
    constructor() {
        this.quizApi = new Api('AdminAjax');
    }

    getQuestions(quizId) {
        return this.quizApi.getResponseAsync('getQuestions', {quizId});
    }

    updateQuiz(quiz) {
        return this.quizApi.getResponseAsync('updateQuiz', {quiz});
    }

    updateQuestion(question) {
        return this.quizApi.getResponseAsync('updateQuestion', {question});
    }

    updateAnswer(answer) {
        return this.quizApi.getResponseAsync('updateAnswer', {answer});
    }

    deleteQuiz(quiz) {
        return this.quizApi.getResponseAsync('deleteQuiz', {quiz});
    }

    deleteQuestion(question) {
        return this.quizApi.getResponseAsync('deleteQuestion', {question});
    }

    deleteAnswer(answer) {
        return this.quizApi.getResponseAsync('deleteAnswer', {answer});
    }
}

export default new AdminRepository();