
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));
const itemsComp = Vue.component('items', require('./components/Items.vue'));
const loginComp = Vue.component('login', require('./components/auth/Login.vue'));
const logoutComp = Vue.component('logout', require('./components/auth/Logout.vue'));
const registerComp = Vue.component('register', require('./components/auth/Register.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const routes = [
    { path: '/', redirect: '/items' },
    { path: '/items', component: itemsComp },
    { path: '/login', component: loginComp },
    { path: '/logout', component: logoutComp },
    { path: '/register', component: registerComp },
];

const router = new VueRouter({
    routes:routes
});

const app = new Vue({
    el: '#app',
    router
});


