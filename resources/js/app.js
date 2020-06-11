require('./bootstrap');
import { routes } from './routes'
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

window.Vue = require('vue');

Vue.use(VueRouter)
Vue.use(Vuetify)

const vuetify = new Vuetify();
const router = new VueRouter({
    mode: 'history',
    routes
})

const app = new Vue({
    el: '#app',
    router,
    vuetify
});
