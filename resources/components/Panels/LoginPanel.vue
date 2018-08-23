<template>
    <div class="login">
        <h1 class="login__header">Register</h1>
        <TextInput class="login__input"  :onEnter="login" v-model="name" label="Your name" placeholder="Name"/>
        <TextInput v-if="name==='admin'" class="login__input" type="password" :onEnter="login"
                   v-model="password" label="Password" placeholder="Password"/>
        <ErrorMessageItem />
        <button class="login__btn" @click="login">Register</button>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import TextInput from '../forms/text.input';
    import ErrorMessageItem from "../Items/ErrorMessageItem";
    export default {
        name: 'LoginPanel',
        components: {ErrorMessageItem, TextInput },
        data: function() {
            return {
                name: '',
                password: ''
            };
        },
        methods: Object.assign({}, mapActions([
            'register',
            'loginAdmin',
        ]), {
            login() {
                if (this.name==='admin') {
                    return this.loginAdmin({name: this.name, password: this.password});
                }

                this.register(this.name);
            },
        })
    }
</script>