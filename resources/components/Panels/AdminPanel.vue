<template>
    <!-- TODO: split up into components-->
    <div class="admin">
        <div class="admin__navigator">
            <span>
                <a class="admin__navbtn" @click="selectQuiz(null)">Home</a>
            </span>
            <span v-if="selectedQuiz">
                <a class="admin__navbtn" @click="selectQuestion(null)"> -> {{ selectedQuiz.name }}</a>
            </span>
            <span v-if="selectedQuestion">
                <a class="admin__navbtn" @click="selectAnswer(null)"> -> {{ selectedQuestion.question }}</a>
            </span>
            <span v-if="selectedAnswer">
                <a class="admin__navbtn"> -> {{ selectedAnswer.answer }} </a>
            </span>
        </div>

        <!-- ------------------------ ANSWER ------------------------ -->
        <span v-if="selectedAnswer" class="admin__answer">
            <div class="admin__panel">
                <ConfirmationDialog :on-decline="h => {confirmAnswer = false}" :on-accept="deleteAnswer" :show="confirmAnswer"
                                    text="Are you sure you want to delete this answer?"/>
                <h1 class="admin__title">
                    {{ selectedAnswer.answer }}
                </h1>

                <TextInput class="admin__input" label="Answer text" v-model="localAnswer.answer"/>
                <label><input class="admin__checkbox" type="checkbox"
                              v-model="localAnswer.isCorrect"> Is correct</label>

                <div class="admin__controls">
                    <button class="admin__btn" @click="saveAnswer">Save</button>
                    <button class="admin__btn" @click="reset">Reset</button>
                    <button class="admin__btn" @click="confirmAnswer = true">Delete</button>
                </div>
            </div>
        </span>

        <!--  ------------------------ QUESTION ------------------------ -->
        <span v-else-if="selectedQuestion" class="admin__question">
            <div class="admin__panel">
                <ConfirmationDialog :on-decline="h => {confirmQuestion = false}" :on-accept="deleteQuestion" :show="confirmQuestion"
                                    text="Are you sure you want to delete this question?
                                All answers will be deleted as well."/>
                <h1 class="admin__title">
                    {{ selectedQuestion.question }}
                </h1>

                <TextInput class="admin__input" label="Question text" v-model="localQuestion.question"/>
                <TextInput class="admin__input" label="Question number" v-model="localQuestion.questionNo"/>

                <div class="admin__controls">
                    <button class="admin__btn" @click="saveQuestion">Save</button>
                    <button class="admin__btn" @click="reset">Reset</button>
                    <button class="admin__btn" @click="confirmQuestion = true">Delete</button>
                </div>
                <span v-if="selectedQuestion.isValid()">
                    <hr>
                    <h2 class="admin__title admin__title--small">
                        Answers
                    </h2>
                    <div class="admin__selector-container">
                        <button v-for="answer in selectedQuestion.answers" @click="selectAnswer(answer)"
                                :class="{'admin__selector-btn': true, 'font--bold': answer.isCorrect}"> {{ answer.answer }} </button>
                    </div>
                    <button class="admin__add-btn" @click="newAnswer()">Add Answer</button>
                </span>
            </div>
        </span>

        <!-- ------------------------ QUIZ ------------------------ -->
        <span v-else-if="selectedQuiz" class="admin__quiz">
            <div class="admin__panel">
                            <ConfirmationDialog :on-decline="h => {confirmQuiz = false}" :on-accept="deleteQuiz" :show="confirmQuiz"
                                                text="Are you sure you want to delete this quiz?
                                                All questions and answers will be deleted as well."/>
                <h1 class="admin__title">
                    {{ selectedQuiz.name }}
                </h1>
                <TextInput class="admin__input" label="Quiz name" v-model="localQuiz.name"/>
                <div class="admin__controls">
                    <button class="admin__btn" @click="saveQuiz">Save</button>
                    <button class="admin__btn" @click="reset">Reset</button>
                    <button class="admin__btn" @click="confirmQuiz = true">Delete</button>
                </div>
                <span v-if="selectedQuiz.id !== 0">
                    <hr>
                    <h2 class="admin__title admin__title--small">
                        Questions
                    </h2>
                    <div class="admin__selector-container">
                        <button v-for="question in questions" @click="selectQuestion(question)"
                                class="admin__selector-btn"> {{question.questionNo}}: {{ question.question }} </button>
                    </div>
                    <button class="admin__add-btn" @click="newQuestion()">Add question</button>
                </span>
            </div>
        </span>

        <!-- ------------------------ HOME ------------------------ -->
        <span v-else class="admin__home">
            <div class="admin__panel">
                <h1 class="admin__title">Stats</h1>
                <p> Total quiz count: {{ allQuizzes.length }}</p>
            </div>
            <div class="admin__panel">
                <h1 class="admin__title"> Quizzes </h1>
                <div class="admin__selector-container">
                    <button v-for="quiz in allQuizzes" @click="selectQuiz(quiz)" class="admin__selector-btn"> {{ quiz.name }} </button>
                </div>
                <button class="admin__add-btn" @click="newQuiz">Add quiz</button>
            </div>
        </span>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import AdminRepository from '../../repositories/repository.admin';
    import * as Response from '../../models/model.response';
    import SelectDropdown from "../forms/select.dropdown";
    import TextInput from "../forms/text.input";
    import Quiz from "../../models/model.quiz";
    import ConfirmationDialog from "../Items/ConfirmationDialog";
    import Question from "../../models/model.question";
    import Answer from "../../models/model.answer";

    export default {
        name: 'AdminPanel',
        components: {ConfirmationDialog, TextInput, SelectDropdown},
        data: function () {
            return {
                selectedQuiz: null,
                localQuiz: null,
                selectedQuestion: null,
                localQuestion: null,
                selectedAnswer: null,
                localAnswer: null,
                questions: null,

                confirmQuiz: false,
                confirmQuestion: false,
                confirmAnswer: false,
            }
        },
        computed: {
            allQuizzes: {
                get() {
                    return this.$store.state.allQuizzes;
                }
            },
        },
        methods: Object.assign({}, mapActions([
            'setAllQuizzes',
        ]), {
            selectQuiz(quiz) {
                this.selectedQuiz = quiz;
                this.localQuiz = Object.assign({}, this.selectedQuiz); //Make a shallow clone to allow modifying values without saving
                this.selectedQuestion = null;
                this.selectedAnswer = null;
                if (quiz === null) {
                    return;
                }
                this.questions = null;
                this.getQuestions(quiz.id);
            },
            selectQuestion(question) {
                this.selectedQuestion = question;
                this.localQuestion = Object.assign({}, this.selectedQuestion); //Make a shallow clone to allow modifying values without saving
                this.selectedAnswer = null;
                if (question === null) {
                    return;
                }
            },
            selectAnswer(answer) {
                this.selectedAnswer = answer;
                this.localAnswer = Object.assign({}, this.selectedAnswer); //Make a shallow clone to allow modifying values without saving
                if (answer === null) {
                    return;
                }
            },
            getQuestions(quizId) {
                let questionsPromise = AdminRepository.getQuestions(quizId);
                questionsPromise.then(response => {
                    if (response.type === Response.QUESTIONS) {
                        this.questions = response.data;

                        //Needed to update answers if new ones were added
                        if(this.selectedQuestion !== null) {
                            this.questions.forEach(question => {
                                if (question.id === this.selectedQuestion.id) {
                                    this.selectedQuestion = question;
                                }
                            });
                        }
                    } else {
                        alert('error');
                    }
                });
            },
            reset() {
                //Make a shallow clone to allow modifying values without saving
                this.localQuiz = Object.assign({}, this.selectedQuiz);
                this.localQuestion = Object.assign({}, this.selectedQuestion);
                this.localAnswer = Object.assign({}, this.selectedAnswer);
            },
            saveQuiz() {
                //This request is async, refetch quizzes only after it has completed
                AdminRepository.updateQuiz(this.localQuiz)
                    .then(response => {
                        // let quiz = Quiz.fromArray(response.data);
                        // this.selectQuiz(quiz);
                        Object.assign(this.selectedQuiz, response.data);
                        this.setAllQuizzes();
                    });
            },
            saveQuestion() {
                //This request is async, refetch quizzes only after it has completed
                AdminRepository.updateQuestion(this.localQuestion)
                    .then(response => {
                        if (response.type === Response.QUESTION) {
                            // this.selectQuestion(response.data);
                            Object.assign(this.selectedQuestion, response.data);
                            this.getQuestions(this.localQuiz.id);
                        }
                    });
            },
            saveAnswer() {
                //This request is async, refetch quizzes only after it has completed
                this.localAnswer.isCorrect = this.localAnswer.isCorrect ? 1 : 0;
                AdminRepository.updateAnswer(this.localAnswer)
                    .then(response => {
                        if (response.type === Response.ANSWER) {
                            Object.assign(this.selectedAnswer, response.data);
                            // this.selectAnswer(response.data);
                            this.getQuestions(this.localQuiz.id); //required because answers reside in question models
                        }
                    });
            },
            deleteQuiz() {
                this.confirmQuiz = false;
                AdminRepository.deleteQuiz(this.selectedQuiz)
                    .then(response => {
                        if (response.type === Response.STATUS) {
                            if (response.data === true) {
                                //refresh
                                this.setAllQuizzes();
                                this.selectQuiz(null);
                            }
                        }
                    });
            },
            deleteQuestion() {
                this.confirmQuestion = false;
                AdminRepository.deleteQuestion(this.selectedQuestion)
                    .then(response => {
                        if (response.type === Response.STATUS) {
                            if (response.data === true) {
                                this.getQuestions(this.selectedQuiz.id);
                                this.selectQuestion(null);
                            }
                        }
                    });
            },
            deleteAnswer() {
                this.confirmAnswer = false;
                AdminRepository.deleteAnswer(this.selectedAnswer)
                    .then(response => {
                        console.log(response);
                        if (response.type === Response.STATUS) {
                            if (response.data === true) {
                                //refresh
                                console.log(response.data);
                                this.getQuestions(this.selectedQuiz.id);
                                this.selectAnswer(null);
                            }
                        }
                    });
            },
            newQuiz() {
                this.selectQuiz(new Quiz);
            },
            newQuestion() {
                this.selectQuestion(new Question);
                this.localQuestion.quizId = this.selectedQuiz.id;
            },
            newAnswer() {
                this.selectAnswer(new Answer);
                this.localAnswer.questionId = this.selectedQuestion.id;
            }
        }),
        created() {
            this.setAllQuizzes();
        }
    }
</script>