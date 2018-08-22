<template>
    <div>
        <div v-if="!user.isValid()" >
            <!--Please log in:-->
            <!--<input v-model="name" placeholder="Name...">-->
            <TextInput label="Enter your name:  " v-model="name" placeholder="Name" />
            <button @click="register(name)">Register</button>
        </div>
        <!--<div v-else-if="user.isValid()">-->
            <!--Hello {{ user.name }}. Your id is {{ user.id }}.-->
            <!--<br>-->
            <!--<button @click="logout()">Logout</button>-->
        <!--</div>-->
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import TextInput from "./forms/text.input";
    export default {
        name: 'LoginComponent',
        components: {TextInput},
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
            },
            name: {
                get() {
                    return this.$store.state.name;
                },
                set(newName) {
                    this.setName(newName);
                }
            }
        },

        methods: mapActions([
            'setUser',
            'logout',
            'register',
            'setName'
        ]),

        created() {
            this.setUser();
        }
    }
</script>