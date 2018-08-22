<template>
    <span>
        <UserBar/>

        <span v-if="!user.isValid()">
            <Login />
        </span>

        <span v-else-if="user.isValid() && !currentQuestion.isValid() && !result.isValid()">
            <Intro />
        </span>

        <span v-if="user.isValid() && currentQuestion.isValid()">
            <QuestionItem/>
        </span>

        <span v-if="result.isValid()">
            <ResultItem/>
        </span>
    </span>
</template>

<script>
    import QuestionItem from "./QuestionItem";
    import ResultItem from "./ResultItem";
    import Intro from "./Intro";
    import UserBar from "./UserBar";
    import Login from "./Login";

    export default {
        name: 'Quiz',
        components: {Login, UserBar, Intro, ResultItem, QuestionItem},
        computed: {
            user: {
                get() {
                    return this.$store.state.user;
                }
            },
            currentQuestion: {
                get() {
                    return this.$store.state.currentQuestion;
                }
            },
            result: {
                get() {
                    return this.$store.state.currentResult;
                }
            }
        },
    }
</script>