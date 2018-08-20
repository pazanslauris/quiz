import Quiz from "./model.quiz";
import Question from "./model.question";
import Result from "./model.result";

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
        if (type === "question") {
            return Question.fromArray(rawData.response);
        } else if (type === "result") {
            return Result.fromArray(rawData.response);
        } else if (type === "quizzes") {
            return rawData.response.map(Quiz.fromArray);
        } else if (type === "errorMsg") {
            return rawData.response;
        } else if (type === "status") {
            return rawData.response;
        }
        return null;
    }

    static fromArray(rawData) {
        let response = new Response();
        response.type = rawData.type;
        response.data = this.getObjectFromData(response.type, rawData);

        return response;
    }
}