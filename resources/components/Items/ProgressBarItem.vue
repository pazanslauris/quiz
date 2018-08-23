<template>
    <div class="progress">
        <progress class="progress__bar" :value="currentQuestion.questionNo - 1" :max="quiz.totalQuestionCount"></progress>
    </div>
</template>

<script>
    export default {
        name: 'ProgressBarItem',
        computed: {
            currentQuestion: {
                get() {
                    return this.$store.state.currentQuestion;
                }
            },
            quiz: {
                get() {
                    return this.$store.state.currentQuiz;
                }
            },
        }
    }
</script>