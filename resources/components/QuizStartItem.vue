<template>
    <div>
        <SelectDropdown label="Select Quiz: " v-model="activeQuizId" :options="getQuizzes()"/>
        <button @click="onStart()">Start</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import SelectDropdown from "./forms/select.dropdown";

    export default {
        name: 'QuizStartItem',
        components: {SelectDropdown},
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
            },
            getQuizzes() {
                return [].concat([{ id: '', name: '---'}], this.allQuizzes.map(quiz => quiz.toArray()));
            }
        }),

        created() {
            this.setAllQuizzes();
        }
    }
</script>