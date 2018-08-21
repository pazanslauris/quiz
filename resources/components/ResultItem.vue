<template>
    <span>Total answers: {{ result.totalAnswers }}, Correct answers: {{ result.correctAnswers }}</span>
</template>

<script>
    export default {
        name: 'ResultItem',
        computed: {
            result: {
                get() {
                    return this.$store.state.currentResult;
                }
            }
        }
    }
</script>