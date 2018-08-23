export default class Quiz {

    constructor() {
        /**
         * @type {?number}
         */
        this.id = 0;

        /**
         * @type {?string}
         */
        this.name = '';

        /**
         * @type {int}
         */
        this.totalQuestionCount = 0;
    }

    /**
     *
     * @param rawData
     * @returns {Quiz}
     */
    static fromArray(rawData) {
        let quiz = new Quiz();
        quiz.id = rawData.id;
        quiz.name = rawData.name;
        quiz.totalQuestionCount = rawData.totalQuestionCount;


        return quiz;
    }

    /**
     * @return {{}}
     */
    toArray() {
        return {
            id: this.id,
            name: this.name,
        }
    }
}