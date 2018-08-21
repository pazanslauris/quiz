<template>
    <div>
        <div>
            <!--Login-->
            <LoginComponent/>

            <div v-if="user.isValid()">
                <!--Select quiz-->
                <QuizStartItem/>

                <!--Question-->
                <QuestionItem/>

                <!--Result-->
                <ResultItem/>
            </div>

        </div>
    </div>
</template>

<script>
    import QuestionItem from "./QuestionItem";
    import ResultItem from "./ResultItem";
    import QuizStartItem from "./QuizStartItem";
    import LoginComponent from "./LoginComponent";

    export default {
        name: 'Quiz',
        components: {LoginComponent, QuizStartItem, ResultItem, QuestionItem},
        computed: {
            user: {
                get() {
                    return this.$store.state.user;
                }
            }
        },
    }
</script>