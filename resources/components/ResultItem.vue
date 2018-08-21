<template>
    <div v-if="result.isValid()">
        Total answers: {{ result.totalAnswers }}, Correct answers: {{ result.correctAnswers }}
    </div>
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