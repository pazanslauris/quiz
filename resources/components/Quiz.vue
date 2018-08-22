<template>
    <div class="stretch">
            <!--Login-->
            <!--<LoginComponent/>-->
            <!--Select quiz-->
            <!--<QuizStartItem/>-->

            <div class="stretch" v-if="!user.isValid()">
                <Intro />
            </div>

            <div class="stretch" v-if="user.isValid() && currentQuestion.isValid()">
                <!--Question-->
                <QuestionItem/>
            </div>
        
            <div class="stretch" v-if="result.isValid()">
                <!--Result-->
                <ResultItem/>
            </div>
    </div>
</template>

<script>
    import QuestionItem from "./QuestionItem";
    import ResultItem from "./ResultItem";
    import QuizStartItem from "./QuizStartItem";
    import Intro from "./Intro";

    export default {
        name: 'Quiz',
        components: {Intro, QuizStartItem, ResultItem, QuestionItem},
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