import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);



//-----------------Restaurant worker-------------------------
const dashboard = Vue.component('dashboard', require('../components/restaurantWorker/Dashboard.vue')); 
const profileEdit = Vue.component('profileEdit', require('../components/restaurantWorker/ProfileEdit.vue'));



//-----------------------Items--------------------------------
const itemsComponent = Vue.component('items', require('../components/items/Items.vue'));
Vue.component('itemsList', require('../components/items/ItemsList.vue'));
const editItemComponent = Vue.component('itemEdit', require('../components/items/ItemEdit.vue'));
const itemAddComponent = Vue.component('itemAdd', require('../components/items/ItemAdd.vue'));



//------------------------Orders-------------------------------------
const ordersComponent = Vue.component('orders', require('../components/cook/Orders.vue'));
Vue.component('orders-list', require('../components/cook/OrdersList.vue'));


//---------------------Cashier--------------------------------------
const invoicesComponent = Vue.component('pending-invoices', require('../components/cashier/Invoices.vue'));
Vue.component('invoices-list', require('../components/cashier/InvoicesList.vue'));
Vue.component('edit-nif-name', require('../components/cashier/InvoicesNifName.vue'));
Vue.component('invoice-details', require('../components/cashier/InvoiceDetails.vue'));




//-----------------------------Waiter---------------------------
const meals_of_waiter = Vue.component('waiterMeals', require('../components/waiter/Meals.vue'));
Vue.component('meals-list', require('../components/waiter/MealsList.vue'));
const create_meal = Vue.component('create-meal', require('../components/waiter/CreateMeals.vue'));



//-------------------------Manager---------------------------
const tablesComponent = Vue.component('tables', require('../components/manager/Tables.vue'));
Vue.component('tables-list', require('../components/manager/TablesList.vue'));
const addEditTableComponent = Vue.component('add-edit-table', require('../components/manager/AddEditTable.vue'));
const managerUsersComponent = Vue.component('users', require('../components/manager/Users/Users.vue'));
Vue.component('userEdit', require('../components/manager/Users/UserEdit.vue'));
Vue.component('usersList', require('../components/manager/Users/UsersList.vue'));
const userAddComponent = Vue.component('userAdd', require('../components/manager/Users/UserAdd.vue'));
const managerMealsComponent = Vue.component('meals', require('../components/manager/Meals/Meals.vue'));
Vue.component('mealsListManager', require('../components/manager/Meals/MealsList.vue'));
Vue.component('mealDetailsList', require('../components/manager/Meals/MealDetailsList.vue'));
const managerInvoicesComponent = Vue.component('invoices', require('../components/manager/Invoices/Invoices.vue'));
Vue.component('invoicesListManager', require('../components/manager/Invoices/InvoicesList.vue'));


//-------------------------Utils-------------------------
Vue.component('navbar', require('../components/utils/Navbar.vue'));
Vue.component('pagination', require('../components/utils/Pagination.vue'));



//----------------------Auth---------------------------
const login = Vue.component('login', require('../components/auth/login.vue'));
const logout = Vue.component('logout', require('../components/auth/logout.vue'));



const statisticsComponent = Vue.component('statistics', require('../components/manager/Statistics.vue'));

const routes = [
    //--------------Items---------------------
    {
        path: '/', 
        redirect: '/items'
    },
    {
        path: '/items', 
        component: itemsComponent
    },




    //-------------------Cooks-------------------
    {
        path: '/statistics', 
        component: statisticsComponent,
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




    //-------------------Cashiers-------------------
    {
        path: '/invoices', 
        component: invoicesComponent,
        meta: {
            forAuth: true
        }
    },




    //------------------Waiters---------------------
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




    //----------------------Managers-----------------------
    {
        path: '/tables', 
        component: tablesComponent,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/addTable', 
        component: addEditTableComponent,
        name: 'addTable',
        meta: {
            forAuth: true
        },
        props: true,
    },
    {
        path: '/editTable', 
        component: addEditTableComponent,
        name: 'editTable',
        meta: {
            forAuth: true
        },
        props: true,
    },
    {
        path: '/managerMeals', 
        component: managerMealsComponent,
        meta: {
            forManager: true,
        }
    },
    {
        path: '/managerUsers', 
        component: managerUsersComponent,
        meta: {
            forAuth: true,
            forManager: true
        }
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
        path: '/editItem', 
        component: editItemComponent,
        name: 'editItem',
        meta: {
            forAuth: true
        },
        props: true,
    },
    {
        path: '/managerInvoices', 
        component: managerInvoicesComponent,
        meta: {
            forAuth: true,
            forManager: true
        }
    },



    //-----------------Restaurant worker-----------------------
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
    
    
    //---------------Auth----------------------
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
