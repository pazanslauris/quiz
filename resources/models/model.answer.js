export default class Answer {

    constructor() {
        /**
         * @type {?number}
         */
        this.id = 0;

        /**
         * @type {?string}
         */
        this.answer = '';

        /**
         * @type {?number}
         */
        this.questionId = 0;

        /**
         * used only for the admin panel...
         * @type {boolean}
         */
        this.isCorrect = false;
    }

    static fromArray(rawData) {
        let answer = new Answer();
        answer.id = rawData.id;
        answer.answer = rawData.answer;
        answer.questionId = rawData.questionId;
        answer.isCorrect = parseInt(rawData.isCorrect);
        return answer;
    }
}