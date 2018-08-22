import Api from '../api.js';
import Response from "../models/model.response";

class QuizRepository {
    constructor() {
        this.quizApi = new Api('QuizAjax');
    }

    getAllQuizzes() {
        return new Promise(resolve => {
            this.quizApi.get('getQuizzes')
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }

    startQuiz(quizId) {
        return new Promise(resolve => {
            this.quizApi.post('startQuiz', {quizId})
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }

    submitAnswer(answerId) {
        return new Promise(resolve => {
            this.quizApi.post('submitAndLoadNextQuestion', {answerId})
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }

    logout() {
        return new Promise(resolve => {
            this.quizApi.post('logout')
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }
}

export default new QuizRepository();