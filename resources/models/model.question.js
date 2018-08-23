import Answer from './model.answer';

export default class Question {

    constructor() {
        /**
         * @type {?number}
         */
        this.id = 0;

        /**
         * @type {?string}
         */
        this.question = '';

        /**
         * @type {?number}
         */
        this.quizId = null;

        /**
         * @type {?number}
         */
        this.questionNo = null;

        /**
         * @type {Answer}
         */
        this.answers = [];
    }

    static fromArray(rawData) {
        let question = new Question();
        question.id = rawData.question.id;
        question.question = rawData.question.question;
        question.quizId = rawData.question.quizId;
        question.questionNo = rawData.question.questionNo;

        question.answers = rawData.answers.map(Answer.fromArray);

        return question;
    }

    isValid() {
        return (this.id !== 0 && this.quizId != null && this.questionNo != null);
    }
}