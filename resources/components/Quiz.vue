<template>
    <span>
        <UserBarPanel/>

        <span v-if="!user.isAdmin">
            <span v-if="!user.isValid()">
                <LoginPanel />
            </span>

            <span v-else-if="user.isValid() && !currentQuestion.isValid() && !result.isValid()">
                <IntroPanel />
            </span>

            <span v-if="user.isValid() && currentQuestion.isValid()">
                <QuestionPanel/>
            </span>

            <span v-if="result.isValid()">
                <ResultPanel/>
            </span>
        </span>
        <span v-else-if="user.isAdmin">
            <AdminPanel />
        </span>


    </span>
</template>

<script>
    import QuestionPanel from "./Panels/QuestionPanel";
    import ResultPanel from "./Panels/ResultPanel";
    import IntroPanel from "./Panels/IntroPanel";
    import UserBarPanel from "./Panels/UserBarPanel";
    import LoginPanel from "./Panels/LoginPanel";
    import AdminPanel from "./Panels/AdminPanel";

    export default {
        name: 'Quiz',
        components: {AdminPanel, LoginPanel, UserBarPanel, IntroPanel, ResultPanel, QuestionPanel},
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