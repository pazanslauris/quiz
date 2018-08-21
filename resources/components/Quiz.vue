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
                <span v-if="question.isValid()"> Question: {{ question.question }}</span>
                <br>
                <button v-for="answer in question.answers" @click="submitAnswer(answer.id)"> {{ answer.answer }}</button>
                <br>
                <span v-if="currentResult.totalAnswers > 0"> Total answers: {{ currentResult.totalAnswers }},
                    Correct answers: {{ currentResult.correctAnswers }}</span>
            </div>


            <!--<input v-if="!user.isValid()" v-model="name" placeholder="Name...">-->
            <!--<select v-model="activeQuizId">-->
                <!--<option v-for="quiz in allQuizzes" :value="quiz.id"> {{ quiz.name }}</option>-->
            <!--</select>-->
            <!--<button @click="onStart()">Start</button>-->
            <!--<button @click="logout()">Logout</button>-->
            <!--<br>-->
            <!--<span v-if="question.isValid()"> Question: {{ question.question }}</span>-->
            <!--<br>-->
            <!--<button v-for="answer in question.answers" @click="submitAnswer(answer.id)"> {{ answer.answer }}</button>-->
            <!--<br>-->
            <!--<span v-if="currentResult.totalAnswers > 0"> Total answers: {{ currentResult.totalAnswers }}, Correct answers: {{ currentResult.correctAnswers }}</span>-->
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
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
                if (!this.name) {
                    alert('Please enter your name.');
                    return;
                }

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