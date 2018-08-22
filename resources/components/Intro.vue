<template>
    <div class="intro">
        <h1 class="intro__header">Pick a quiz</h1>
        <!--<TextInput class="intro__input" v-model="name" label="Your name" placeholder="Name"/>-->
        <SelectDropdown class="intro__input" v-model="activeQuizId" label="Pick your quiz" :options="getQuizzes()" />
        <button class="intro__btn" @click="onStart">Start</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import TextInput from "./forms/text.input";
    import SelectDropdown from "./forms/select.dropdown";

    export default {
        name: 'Intro',
        components: {TextInput, SelectDropdown},
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
            'setActiveQuizId',
            'setName',
            'start',
            'setUser',
        ]), {
            onStart() {
                // if (!this.name) {
                //     alert('Give me your name');
                //     return;
                // }
                if (!this.activeQuizId) {
                    alert('Pick a quiz!');
                    return;
                }
                this.start();
                //this.setUser();
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