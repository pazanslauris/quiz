<template>
    <div class="intro">
        <h1 class="intro__header">Pick a quiz</h1>
        <!--<TextInput class="intro__input" v-model="name" label="Your name" placeholder="Name"/>-->
        <SelectDropdown class="intro__input" v-model="activeQuizId" label="Pick your quiz" :options="getQuizzes()" />
        <ErrorMessageItem />
        <button class="intro__btn" @click="onStart">Start</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import TextInput from "../forms/text.input";
    import SelectDropdown from "../forms/select.dropdown";
    import ErrorMessageItem from "../Items/ErrorMessageItem";

    export default {
        name: 'IntroPanel',
        components: {TextInput, SelectDropdown, ErrorMessageItem},
        data: function() {
            return {
                activeQuizId: null,
            };
        },
        computed: {
            allQuizzes: {
                get() {
                    return this.$store.state.allQuizzes;
                }
            },
            name: {
                get() {
                    return this.$store.state.name;
                },
                set(newName) {
                    this.setName(newName);
                }
            },
        },
        methods: Object.assign({}, mapActions([
            'setAllQuizzes',
            'setCurrentQuiz',
            'setName',
            'start',
            'setUser',
        ]), {
            onStart() {
                if (!this.activeQuizId) {
                    alert('Pick a quiz!');
                    return;
                }

                this.start(this.activeQuizId);
            },
            getQuizzes() {
                return [].concat([{id: '', name: '---'}], this.allQuizzes.map(quiz => quiz.toArray()));
            }
        }),
        created() {
            this.setAllQuizzes();
        }
    }
</script>