import Vue from 'vue';
import Vuex from 'vuex';
import * as types from './mutations.js';

import QuizRepository from '../repositories/repository.quiz.js';
import UserRepository from '../repositories/repository.user.js';
import Question from "../models/model.question";
import Result from "../models/model.result";
import * as Response from "../models/model.response";
import User from "../models/model.user";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        activeQuizId: null,
        allQuizzes: [],
        name: '',
        currentQuestion: new Question(),
        currentResult: new Result(),
        user: new User(),
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
        },
        [types.SET_USER](state, user) {
            state.user = user;
        }
    },
    actions: {
        setActiveQuizId(context, quizId) {
            context.commit(types.SET_ACTIVE_QUIZ, quizId);
        },
        setAllQuizzes(context) {
            let quizPromise = QuizRepository.getAllQuizzes();
            quizPromise.then(response => {
                if (response.type === Response.QUIZZES) {
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
                if (response.type === Response.QUESTION) {
                    //we got the first question...
                    context.commit(types.SET_CURRENT_QUESTION, response.data);
                    context.commit(types.SET_CURRENT_RESULT, new Result());
                    //this.setUser(context);
                } else if (response.type === Response.RESULT) {
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
                if (response.type === Response.QUESTION) {
                    //we have the next question...
                    context.commit(types.SET_CURRENT_QUESTION, response.data);
                    context.commit(types.SET_CURRENT_RESULT, new Result());
                } else if (response.type === Response.RESULT) {
                    context.commit(types.SET_CURRENT_QUESTION, new Question());
                    context.commit(types.SET_CURRENT_RESULT, response.data);
                } else {
                    alert('an error occurred...');
                }
            })
        },
        register(context, name) {
            let registerPromise = UserRepository.newUser(name);
            registerPromise.then(response => {
                if (response.type === Response.USER) {
                    context.commit(types.SET_USER, response.data);
                } else {
                    context.commit(types.SET_USER, new User());
                }
            })
        },
        logout(context) {
            let logoutPromise = UserRepository.logout();
            logoutPromise.then(response => {
                if (response.type === Response.STATUS) {
                    if (response.data === true) {
                        context.commit(types.SET_USER, new User());
                    }
                }
            });
        },
        setUser(context) {
            let userPromise = UserRepository.getUser();
            userPromise.then(response => {
                if (response.type === Response.USER) {
                    context.commit(types.SET_USER, response.data);
                } else if (response.type === Response.ERRORMSG) {
                    //not logged in
                } else {
                    alert('unexpected response');
                }
            })
        }
    }
});