<template>
    <a href="#" @click="onAnswerClicked">{{ answer.answer }}</a>
</template>

<script>
    import Answer from "../models/model.answer";

    export default {
        name: 'AnswerItem',
        props: {
            answer: {
                type: Answer,
                required: true
            },
            onAnswered: {
                type: Function,
                required: true
            }
        },
        methods: {
            onAnswerClicked() {
                this.onAnswered(this.answer.id);
            }
        }
    }
</script>