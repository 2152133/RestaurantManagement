/*jshint esversion: 6 */

'use strict';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
//const VueRouter = require('vue-router');
Vue.use(VueRouter);

const ordersListComponent = Vue.component('orders-list', require('./components/OrdersList.vue'));
const OrdersComponent = Vue.component('orders', require('./components/Orders.vue'));

const routes = [
    {path: '/orders', component: OrdersComponent},
]
 
const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    el: '#app',
    router: router
});
