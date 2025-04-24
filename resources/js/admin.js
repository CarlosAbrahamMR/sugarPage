/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;
window.axios = require('axios').default;

import vuetify from './plugins/vuetify'

axios.defaults.headers.common["Accept"] = "application/json";

const app = new Vue({
    el: '#app',
    vuetify
});
