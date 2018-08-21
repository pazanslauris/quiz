<template>
    <div>
        <div v-if="!user.isValid()" >
            Please log in:
            <input v-model="name" placeholder="Name...">
            <button @click="register(name)">Register</button>
        </div>
        <div v-else-if="user.isValid()">
            Hello {{ user.name }}. Your id is {{ user.id }}.
            <br>
            <button @click="logout()">Logout</button>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    export default {
        name: 'LoginComponent',

        data: function() {
            return {
                name: '',
            };
        },

        computed: {
            user: {
                get() {
                    return this.$store.state.user;
                }
            }
        },

        methods: mapActions([
            'setUser',
            'logout',
            'register'
        ]),

        created() {
            this.setUser();
        }
    }
</script>