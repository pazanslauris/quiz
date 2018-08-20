export default class Answer {

    constructor() {
        /**
         * @type {?number}
         */
        this.id = null;

        /**
         * @type {?string}
         */
        this.answer = '';

        /**
         * @type {?number}
         */
        this.questionId = 0;
    }

    static fromArray(rawData) {
        let answer = new Answer();
        answer.id = rawData.id;
        answer.answer = rawData.answer;
        answer.questionId = rawData.questionId;
        return answer;
    }
}