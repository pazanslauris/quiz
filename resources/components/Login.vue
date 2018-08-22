<template>
    <div class="login">
        <h1 class="login__header">Register</h1>
        <TextInput class="login__input"  :onEnter="register" v-model="name" label="Your name" placeholder="Name"/>
        <button class="login__btn" @click="register(name)">Register</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import TextInput from './forms/text.input';
    export default {
        name: 'Login',
        components: { TextInput },
        data: function() {
            return {
                name: ''
            };
        },
        methods: mapActions([
            'register'
        ]),
    }
</script>