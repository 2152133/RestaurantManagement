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
        pendingOrders: [],
        pendingOrdersMeta:[],
        pendingOrdersLinks:[],
        confirmedUserOrders: [],
        confirmedUserOrdersMeta:[],
        confirmedUserOrdersLinks:[],
    },
    getters,
    mutations,
    actions
});