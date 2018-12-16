
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
const itemsComponent = Vue.component('items', require('./components/Items.vue'));
const navbarComponent = Vue.component('navbar', require('./components/Navbar.vue'));
const ordersComponent = Vue.component('orders', require('./components/Orders.vue'));
const ordersListComponent = Vue.component('orders-list', require('./components/OrdersList.vue'));

const routes = [
    {path: '/', redirect: '/orders'},
    {path: '/orders', component: ordersComponent},
    {path: '/ordersList', component: ordersListComponent},
    {path: '/items', component: itemsComponent},
];

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router,
});


