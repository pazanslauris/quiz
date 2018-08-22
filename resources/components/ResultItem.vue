<template>
    <div class="result" v-if="result.isValid()">
        <h1 class="result__header" >Paldies, {{ name }}</h1>
        <p class="result__text">Tu pareizi atbildēji uz {{ result.correctAnswers}} no {{ result.totalAnswers }} jautājumiem.</p>

        <button class="result__btn" @click="logout()">Logout</button>
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
            name: {
                get() {
                    return this.$store.state.name;
                }
            }
        },
        methods: mapActions([
            'logout',
        ]),
    }
</script>