import Vue from 'vue';
import Quiz from './components/Quiz.vue';
import store from './store/store.js';
import './styles/main.scss';

new Vue({
    el: '#app',
    store, //state
    render: h => h(Quiz),
});