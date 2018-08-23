<template>
    <div class="userBar">
        <span v-if="user.isAdmin" class="userBar__text">Admin mode</span>
        <div class="userBar__info" v-if="user.isValid()">
            <span class="userBar__text">{{ user.name }}</span>
            <!--<span class="userBar__text">Id: {{ user.id }}</span>-->
            <a class="userBar__btn" @click="logout()">Logout</a>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    export default {
        name: 'UserBarPanel',
        computed: {
            user: {
                get() {
                    return this.$store.state.user;
                }
            },
        },

        methods: mapActions([
            'setUser',
            'logout',
        ]),

        created() {
            this.setUser();
        }
    }
</script>