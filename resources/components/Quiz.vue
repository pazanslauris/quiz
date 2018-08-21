<template>
    <div>
        <div>
            <!--Login div-->
            <div v-if="!user.isValid()" >
                Please log in:
                <input v-model="name" placeholder="Name...">
                <button @click="register(name)">Register</button>
            </div>

            <!-- Private div -->
            <div v-if="user.isValid()">
                Hello {{ user.name }}. Your id is {{ user.id }}.
                <br>
                <button @click="logout()">Logout</button>
                <br>

                Active quiz id is {{ activeQuizId }}<br>
                Select quiz:
                <select v-model="activeQuizId">
                    <option v-for="quiz in allQuizzes" :value="quiz.id"> {{ quiz.name }}</option>
                </select>

                <button @click="onStart()">Start</button>
                <br>

                <!--Question-->
                <div v-if="question.isValid()">
                    <QuestionItem />
                </div>

                <!--Result-->
                <div v-else-if="currentResult.isValid()">
                    <ResultItem />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import QuestionItem from "./QuestionItem";
    import ResultItem from "./ResultItem";

    export default {
        name: 'Quiz',
        components: {ResultItem, QuestionItem},
        computed: {
            name: {
                get() {
                    return this.$store.state.name;
                },
                set(newName) {
                    this.setName(newName);
                }
            },
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
            question: {
                get() {
                    return this.$store.state.currentQuestion;
                }
            },
            currentResult: {
                get() {
                    return this.$store.state.currentResult;
                }
            },
            user: {
                get() {
                    return this.$store.state.user;
                }
            }
        },
        methods: Object.assign({}, mapActions([
            'setAllQuizzes',
            'setActiveQuizId',
            'setName',
            'start',
            'submitAnswer',
            'logout',
            'setUser',
            'register',
        ]), {
            // add methods here
            onStart() {
                // if (!this.name) {
                //     alert('Please enter your name.');
                //     return;
                // }

                if (!this.activeQuizId) {
                    alert('Pick a quiz!');
                    return;
                }

                this.start();
            }
        }),
        created() {
            this.setAllQuizzes();
            this.setUser();
        }
    }
</script>