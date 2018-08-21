<template>
    <div>
        <p>Select quiz:</p>
        <select v-model="activeQuizId">
            <option v-for="quiz in allQuizzes" :value="quiz.id"> {{ quiz.name }}</option>
        </select>
        <button @click="onStart()">Start</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        name: 'QuizStartItem',

        computed: {
            activeQuizId: {
                get() {
                    return this.$store.state.activeQuizId;
                },
                set(newValue) {
                    this.setActiveQuizId(newValue);
                }
            },

            allQuizzes: {
                get() {
                    return this.$store.state.allQuizzes;
                }
            },
        },

        methods: Object.assign({}, mapActions([
            'start',
            'setActiveQuizId',
            'setAllQuizzes',
        ]), {
            onStart() {
                if (!this.activeQuizId) {
                    alert('Pick a quiz!');
                    return;
                }

                this.start();
            }
        }),

        created() {
            this.setAllQuizzes();
        }
    }
</script>