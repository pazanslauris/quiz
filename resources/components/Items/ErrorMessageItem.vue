<template>
    <span class="error-msg"> {{ msg }} </span>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        name: 'ErrorMessageItem',
        computed: {
            msg: {
                get() {
                    return this.$store.state.currentErrorMsg;
                }
            }
        },
        methods: mapActions([ 'resetError' ]),
        created() {
            this.resetError();
        }
    }
</script>