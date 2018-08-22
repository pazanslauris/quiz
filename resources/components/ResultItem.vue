<template>
    <div class="result" v-if="result.isValid()">
        <h1 class="result__header" >Paldies, {{ user.name }}</h1>
        <p class="result__text">Tu pareizi atbildēji uz {{ result.correctAnswers}} no {{ result.totalAnswers }} jautājumiem.</p>

        <button class="result__btn" @click="reset()">Do another quiz</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        name: 'ResultItem',
        computed: {
            result: {
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
        methods: mapActions([
            'reset',
        ]),
    }
</script>