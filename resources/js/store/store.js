import Vue from 'vue';
import Vuex from 'vuex';

import getters from './getters';
import mutations from './mutations';
import actions from './actions';
import Axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: { 
        user: {},
        tokenType: "",
        token: "",
        expiration: 0,

        //--------------Orders-------------------------
        confirmedOrders: [],
        confirmedOrdersMeta:[],
        confirmedOrdersLinks:[],
        inPreparationUserOrders: [],
        inPreparationUserOrdersMeta:[],
        inPreparationUserOrdersLinks:[],
        currentOrder: {},

        //----------------Invoices----------------------
        currentInvoice: {},
        pendingInvoices: [],
        pendingInvoicesMeta:[],
        pendingInvoicesLinks:[],
        paidInvoices: [],
        paidInvoicesMeta:[],
        paidInvoicesLinks:[],
        editingNifName: false,
        viewingDetails: false,
        


        //-----------------Meals------------------------
        userMeals: [],
        userMealsMeta: [],
        userMealsLinks: [],
        currentMeal: {},
        allMealOrders: [],
        pendingMealOrders: [],
        pendingMealOrdersMeta: [],
        pendingMealOrdersLinks: [],
        confirmedMealOrders: [],
        confirmedMealOrdersMeta: [],
        confirmedMealOrdersLinks: [],
        preparedMealOrders: [],
        preparedMealOrdersMeta: [],
        preparedMealOrdersLinks: [],
        notDeliveredOrdersOfMeal: [],
        allItems: [],
        mealDetails: [],
        currentMealOrder: {},
        counter: 0,




        //---------------Management---------------------
        tables:[],
        currentTable:{},
        tablesMeta:{},
        tablesLinks:{},
        editingTable: false,
        creatingTable: false,
        currentItem: null,
        items: [],
        itemsMeta: [],
        itemsLinks: [],

    },
    getters,
    mutations,
    actions
});