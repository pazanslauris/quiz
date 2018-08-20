import Vue from 'vue';
import Vuex from 'vuex';
import * as types from './mutations.js';

import QuizRepository from '../repositories/repository.quiz.js';
import Question from "../models/model.question";
import Result from "../models/model.result";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        activeQuizId: null,
        allQuizzes: [],
        name: '',
        currentQuestion: new Question(),
        currentResult: new Result(),
    },
    mutations: {
        [types.SET_ACTIVE_QUIZ](state, quizId) {
            state.activeQuizId = quizId;
        },
        [types.SET_ALL_QUIZZES](state, quizzes) {
            state.allQuizzes = quizzes;
        },
        [types.SET_NAME](state, name) {
            state.name = name;
        },
        [types.SET_CURRENT_QUESTION](state, question) {
            state.currentQuestion = question;
        },
        [types.SET_CURRENT_RESULT](state, result) {
            state.currentResult = result;
        }
    },
    actions: {
        setActiveQuizId(context, quizId) {
            context.commit(types.SET_ACTIVE_QUIZ, quizId);
        },
        setAllQuizzes(context) {
            let quizPromise = QuizRepository.getAllQuizzes();
            quizPromise.then(response => {
                if (response.type === 'quizzes') {
                    context.commit(types.SET_ALL_QUIZZES, response.data);
                }
            });
        },
        setName(context, newName) {
            context.commit(types.SET_NAME, newName);
        },

        start(context) {
            let quizStartPromise = QuizRepository.startQuiz(this.state.name, this.state.activeQuizId);
            quizStartPromise.then(response => {
                if (response.type === 'question') {
                    //we got the first question...
                    context.commit(types.SET_CURRENT_QUESTION, response.data);
                    context.commit(types.SET_CURRENT_RESULT, new Result());
                } else if (response.type === 'result') {
                    context.commit(types.SET_CURRENT_QUESTION, new Question());
                    context.commit(types.SET_CURRENT_RESULT, response.data);
                } else {
                    //failed to start quiz
                    alert('failed to start quiz...');
                }
            })
        },

        submitAnswer(context, answerId) {
            let answerPromise = QuizRepository.submitAnswer(answerId);
            answerPromise.then(response => {
                if (response.type === 'question') {
                    //we have the next question...
                    context.commit(types.SET_CURRENT_QUESTION, response.data);
                    context.commit(types.SET_CURRENT_RESULT, new Result());
                } else if (response.type === 'result') {
                    context.commit(types.SET_CURRENT_QUESTION, new Question());
                    context.commit(types.SET_CURRENT_RESULT, response.data);
                } else {
                    alert('an error occurred...');
                }
            })
        },
        logout(context) {
            let logoutPromise = QuizRepository.logout();
        }
    }
});