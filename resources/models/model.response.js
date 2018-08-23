import Quiz from "./model.quiz";
import Question from "./model.question";
import Result from "./model.result";
import User from "./model.user";
import Answer from "./model.answer";

export const USER = 'user';
export const QUIZ = 'quiz';
export const QUIZZES = 'quizzes';
export const QUESTION = 'question';
export const QUESTIONS = 'questions';
export const ANSWER = 'answer';
export const RESULT = 'result';
export const STATUS = 'status';
export const ERRORMSG = 'errorMsg';

export default class Response {

    constructor() {
        /**
         * @type {?number}
         */
        this.type = null;

        /**
         * Holds the object from the response.
         */
        this.data = null;
    }


    //Is there a better way?
    static getObjectFromData(type, rawData) {
        if (type === QUESTION) {
            return Question.fromArray(rawData.response);
        } else if (type === QUESTIONS) {
            return rawData.response.map(Question.fromArray);
        } else if (type === ANSWER) {
            return Answer.fromArray(rawData.response);
        } else if (type === RESULT) {
            return Result.fromArray(rawData.response);
        } else if (type === QUIZZES) {
            return rawData.response.map(Quiz.fromArray);
        } else if (type === QUIZ)  {
            return Quiz.fromArray(rawData.response);
        } else if (type === USER) {
            return User.fromArray(rawData.response);
        } else if (type === ERRORMSG) {
            return rawData.response;
        } else if (type === STATUS) {
            return rawData.response;
        }
        return null;
    }

    static fromArray(rawData) {
        // console.log(rawData);
        let response = new Response();
        response.type = rawData.type;
        response.data = this.getObjectFromData(response.type, rawData);

        return response;
    }
}