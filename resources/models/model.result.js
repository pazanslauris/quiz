export default class Result {
    constructor() {
        /**
         * @type {?number}
         */
        this.correctAnswers = null;

        /**
         * @type {?string}
         */
        this.totalAnswers = null;
    }

    static fromArray(rawData) {
        let result = new Result();
        result.correctAnswers = rawData.correctAnswers;
        result.totalAnswers = rawData.totalAnswers;

        return result;
    }
}