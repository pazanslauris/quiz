<template>
    <div class="viewer" v-if="question.isValid()">
        <h1 class="viewer__question">{{ question.question }}</h1>
        <ul class="answers">
            <li class="answers__item" v-for="answer in question.answers">
                <AnswerItem :is-active="(answer.id === answerId)" :answer="answer" :on-answered="onAnswerPicked"></AnswerItem>
            </li>
        </ul>
        <button class="viewer__btn" @click="onAnswered">Next question</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import AnswerItem from "./AnswerItem";

    export default {
        data() {
            return {
                answerId: null,
            }
        },
        name: "QuestionItem",
        components: {AnswerItem},
        computed: {
            question: {
                get() {
                    return this.$store.state.currentQuestion;
                }
            }
        },
        methods: Object.assign({}, mapActions([
            'submitAnswer',
        ]), {
            // add methods here
            onAnswerPicked(answerId) {
                this.answerId = answerId;
            },
            onAnswered() {
                if (!this.answerId) {
                    alert('no answer selected');
                    return;
                }
                this.submitAnswer(this.answerId);
            }
        }),
    }
</script>