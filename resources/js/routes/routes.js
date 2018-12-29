import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
const itemsComponent = Vue.component('items', require('../components/items/Items.vue'));
Vue.component('navbar', require('../components/utils/Navbar.vue'));
const ordersComponent = Vue.component('orders', require('../components/cook/Orders.vue'));
Vue.component('orders-list', require('../components/cook/OrdersList.vue'));
Vue.component('pagination', require('../components/utils/Pagination.vue'));
const dashboard = Vue.component('dashboard', require('../components/restaurantWorker/Dashboard.vue')); 
const invoicesComponent = Vue.component('pending-invoices', require('../components/cashier/Invoices.vue'));
Vue.component('invoices-list', require('../components/cashier/InvoicesList.vue'));
Vue.component('edit-nif-name', require('../components/cashier/InvoicesNifName.vue'));
Vue.component('invoice-details', require('../components/cashier/InvoiceDetails.vue'));
const meals_of_waiter = Vue.component('waiterMeals', require('../components/Meals.vue'));
Vue.component('meals-list', require('../components/MealsList.vue'));
Vue.component('edit-nif-name', require('../components/cashier/InvoicesNifName.vue'));
const create_meal = Vue.component('create-meal', require('../components/CreateMeals.vue'));
Vue.component('itemsList', require('../components/items/ItemsList.vue'));
const profileEdit = Vue.component('profileEdit', require('../components/restaurantWorker/ProfileEdit.vue'));
Vue.component('itemEdit', require('../components/items/ItemEdit.vue'));
const login = Vue.component('login', require('../components/auth/login.vue'));
const logout = Vue.component('logout', require('../components/auth/logout.vue'));
const usersComponent = Vue.component('users', require('../components/manager/Users.vue'));
Vue.component('userEdit', require('../components/manager/UserEdit.vue'));
Vue.component('usersList', require('../components/manager/UsersList.vue'));
const userAddComponent = Vue.component('userAdd', require('../components/manager/UserAdd.vue'));
const itemAddComponent = Vue.component('itemAdd', require('../components/items/ItemAdd.vue'));
//Vue.component('edit-nif-name', require('./components/cashier/PendingInvoicesNifName.vue'));

const mealsComponent = Vue.component('meals', require('../components/manager/Meals.vue'));
Vue.component('mealsListManager', require('../components/manager/MealsList.vue'));
Vue.component('mealDetailsList', require('../components/manager/MealDetailsList.vue'));


//passport
//Vue.component('passport-clients', require('../components/passport/Clients.vue').default);
//Vue.component('passport-authorized-clients', require('../components/passport/AuthorizedClients.vue').default);
//Vue.component('passport-personal-access-tokens', require('../components/passport/PersonalAccessTokens.vue').default);

const routes = [
    {
        path: '/', 
        redirect: '/items'
    },
    {
        path: '/meals', 
        component: mealsComponent,
        meta: {
            forManager: true,
        }
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
        component: itemsComponent
    },
    {
        path: '/newUser', 
        component: userAddComponent,
        meta: {
            forAuth: true,
            forManager: true
        }
    },
    {
        path: '/newItem', 
        component: itemAddComponent,
        meta: {
            forAuth: true,
            forManager: true
        }
    },
    {
        path: '/users', 
        component: usersComponent,
        meta: {
            forAuth: true,
            forManager: true
        }
    },
    {
        path: '/profileEdit', 
        component: profileEdit,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/dashboard', 
        component: dashboard, 
        name: 'dashboard',
        meta: {
            forAuth: true
        }
    },
    {
        path: '/invoices', 
        component: invoicesComponent,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/mealsOfWaiter', 
        component: meals_of_waiter,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/createMeal', 
        component: create_meal,
        name: 'create_meal',
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
    routes,
    //mode: 'history',
    linkActiveClass: 'active' 
});
