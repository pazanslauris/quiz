import Vue from 'vue';
import Vuex from 'vuex';
import * as types from './mutations.js';

import QuizRepository from '../repositories/repository.quiz.js';
import UserRepository from '../repositories/repository.user.js';
import Question from "../models/model.question";
import Result from "../models/model.result";
import * as Response from "../models/model.response";
import User from "../models/model.user";
import Quiz from "../models/model.quiz";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        currentQuiz: new Quiz(),
        allQuizzes: [],
        currentQuestion: new Question(),
        currentResult: new Result(),
        user: new User(),
        currentErrorMsg: '',
    },
    mutations: {
        [types.SET_CURRENT_QUIZ](state, quiz) {
            state.currentQuiz = quiz;
        },
        [types.SET_ALL_QUIZZES](state, quizzes) {
            state.allQuizzes = quizzes;
        },
        [types.SET_CURRENT_QUESTION](state, question) {
            state.currentQuestion = question;
        },
        [types.SET_CURRENT_RESULT](state, result) {
            state.currentResult = result;
        },
        [types.SET_USER](state, user) {
            state.user = user;
        },
        [types.SET_ERROR_MSG](state, msg) {
            state.currentErrorMsg = msg;
        }
    },
    actions: {
        setCurrentQuiz(context, quizId) {
            for (let i in context.state.allQuizzes) {
                let quiz = context.state.allQuizzes[i];
                if (quiz.id === quizId) {
                    context.commit(types.SET_CURRENT_QUIZ, quiz);
                }
            }
        },

        setAllQuizzes(context) {
            let quizPromise = QuizRepository.getAllQuizzes();
            quizPromise.then(response => {
                if (response.type === Response.QUIZZES) {
                    context.commit(types.SET_ALL_QUIZZES, response.data);
                }
            });
        },

        start(context, quizId) {
            context.dispatch('setCurrentQuiz', quizId);
            let quizStartPromise = QuizRepository.startQuiz(quizId);
            quizStartPromise.then(response => {
                if (response.type === Response.QUESTION) {
                    context.commit(types.SET_CURRENT_QUESTION, response.data);
                    context.commit(types.SET_CURRENT_RESULT, new Result());

                } else if (response.type === Response.RESULT) {
                    context.commit(types.SET_CURRENT_QUESTION, new Question());
                    context.commit(types.SET_CURRENT_RESULT, response.data);

                } else if (response.type === Response.ERRORMSG) {
                    context.commit(types.SET_ERROR_MSG, response.data);
                    context.commit(types.SET_CURRENT_QUIZ, new Quiz());

                } else {
                    alert("Unexpected response received");
                }
            })
        },

        submitAnswer(context, answerId) {
            let answerPromise = QuizRepository.submitAnswer(answerId);
            answerPromise.then(response => {
                if (response.type === Response.QUESTION) {
                    context.commit(types.SET_CURRENT_QUESTION, response.data);

                } else if (response.type === Response.RESULT) {
                    context.commit(types.SET_CURRENT_QUESTION, new Question());
                    context.commit(types.SET_CURRENT_RESULT, response.data);

                } else if (response.type === Response.ERRORMSG) {
                    context.commit(types.SET_ERROR_MSG, response.data);

                } else {
                    alert("Unexpected response received");
                }
            })
        },
        register(context, name) {
            if (name.length < 3) {
                alert('Name can\'t be shorter than 3 letters');
                return;
            }
            let registerPromise = UserRepository.newUser(name);
            registerPromise.then(response => {
                if (response.type === Response.USER) {
                    context.commit(types.SET_USER, response.data);

                } else if (response.type === Response.ERRORMSG) {
                    context.commit(types.SET_ERROR_MSG, response.data);

                } else {
                    alert("Unexpected response received");
                }
            })
        },
        loginAdmin(context, {name, password}) {
            let loginPromise = UserRepository.login(name, password);
            loginPromise.then(response => {
                if (response.type === Response.USER) {
                    context.commit(types.SET_USER, response.data);

                } else if (response.type === Response.ERRORMSG) {
                    context.commit(types.SET_ERROR_MSG, response.data);

                } else {
                    alert("Unexpected response received");
                }
            })
        },
        reset(context) {
            context.commit(types.SET_CURRENT_QUESTION, new Question());
            context.commit(types.SET_CURRENT_RESULT, new Result());
        },
        logout(context) {
            let logoutPromise = UserRepository.logout();
            logoutPromise.then(response => {
                if (response.type === Response.STATUS) {
                    if (response.data === true) {
                        context.commit(types.SET_USER, new User());
                        context.commit(types.SET_CURRENT_QUESTION, new Question());
                        context.commit(types.SET_CURRENT_RESULT, new Result());
                    } else {
                        context.commit(types.SET_ERROR_MSG, "Failed to log out");
                    }
                } else {

                    alert("Unexpected response received");
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
        },
        resetError(context) {
            context.commit(types.SET_ERROR_MSG, '');
        }
    }
});