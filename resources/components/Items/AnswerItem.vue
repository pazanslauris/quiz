<template>
    <div class="answers__label-wrapper">
        <a :class="{ answers__label: true , 'answers__label--active': isActive }"
           @click="onAnswerClicked">{{ answer.answer }}</a>
    </div>
</template>

<script>
    import Answer from "../../models/model.answer";

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
            },
            isActive: {
                type: Boolean,
            }
        },
        methods: {
            onAnswerClicked() {
                this.onAnswered(this.answer.id);
            }
        }
    }
</script>