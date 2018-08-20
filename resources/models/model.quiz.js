export default class Quiz {

    constructor() {
        /**
         * @type {?number}
         */
        this.id = null;

        /**
         * @type {?string}
         */
        this.name = '';
    }

    static fromArray(rawData) {
        let quiz = new Quiz();
        quiz.id = rawData.id;
        quiz.name = rawData.name;

        return quiz;
    }
}