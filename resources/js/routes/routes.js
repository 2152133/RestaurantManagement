import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
const itemsComponent = Vue.component('items', require('../components/items/Items.vue'));
Vue.component('itemsList', require('../components/items/ItemsList.vue'));
Vue.component('profileEdit', require('../components/user/ProfileEdit.vue'));
//Vue.component('item', require('./components/items/Item.vue'));
Vue.component('ItemEdit', require('../components/items/ItemEdit.vue'));
Vue.component('navbar', require('../components/Navbar.vue'));
const ordersComponent = Vue.component('orders', require('../components/Orders.vue'));
Vue.component('orders-list', require('../components/OrdersList.vue'));
Vue.component('pagination', require('../components/pagination.vue'));
const landing_page = Vue.component('landing_page', require('../components/LandingPage.vue')); 
const notifications_page = Vue.component('notifications_page', require('../components/Notifications.vue'));
const pendingInvoicesComponent = Vue.component('pending-invoices', require('../components/PendingInvoices.vue'));
Vue.component('invoices-list', require('../components/InvoicesList.vue'));
Vue.component('edit-nif-name', require('../components/PendingInvoicesNifName.vue'));
const login = Vue.component('login', require('../components/auth/login.vue'));
const logout = Vue.component('logout', require('../components/auth/logout.vue'));

//passport
Vue.component('passport-clients', require('../components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('../components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('../components/passport/PersonalAccessTokens.vue').default);

const routes = [
    {
        path: '/', 
        redirect: '/items'
    },
    {
        path: '/orders', 
        component: ordersComponent,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/items', 
        component: itemsComponent},
    {
        path: '/dashboard', 
        component: landing_page, 
        name: 'dashboard',
        meta: {
            forAuth: true
        }
    },
    {
        path: '/notifications', component: 
        notifications_page, 
        name: 'notifications',
        meta: {
            forAuth: true
        }
    },
    {
        path: '/pendingInvoices', 
        component: pendingInvoicesComponent,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/login', 
        component: login, 
        name: 'login',
        meta: {
            forVisitors: true
        }
    },
    {
        path: '/logout', 
        component: logout, 
        name: 'logout',
        meta: {
            forAuth: true
        }
    },
];

export default new VueRouter({
    routes: routes
});
