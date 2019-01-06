
require('./bootstrap');

window.Vue = require('vue');


import store from './store/store';
import router from './routes/routes';
import VueSocketio from 'vue-socket.io';
import Toasted from 'vue-toasted';
import swal from 'sweetalert';
import VeeValidate from 'vee-validate';
import Vue from 'vue';

Vue.use(Toasted, {
    theme: "bubble",
    position: 'bottom-center',
    duration: 5000,
    type: 'info',
});

Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://127.0.0.1:8080'
})); 


Vue.use(VeeValidate);


// Para manter o utilizador logado depois de refrescar a pagina
store.state.user = store.getters.getAuthUser
store.state.token = store.getters.getToken
store.state.tokenType = store.getters.getTokenType
store.state.getExpiration = store.getters.getExpiration
axios.defaults.headers.common['Authorization'] = 'Bearer ' + store.getters.getToken

// interceptors - pre and post request
axios.interceptors.response.use(
    (response) => {
        //console.log(response)
        return response;
    }, 
    (error) => {
        // error message
        // error.response.data.error
        // error status code
        // error.response.status
        //console.log(error.response.data.error)
        if (error.response.status == 400) {
            swal(error.response.status.toString(), error.response.data.error, 'error')
        }
        else if (error.response.status == 401) {
            swal(error.response.status.toString(), error.response.data.error, 'error')
        }
        else if (error.response.status == 404) {
            swal(error.response.status.toString(), 'Resource not found.', 'error')
        }
        else if (error.response.status == 422) {
            if (error.response.data.errors.email)
                swal(error.response.status.toString(), error.response.data.errors.email[0], 'error')
            else if (error.response.data.errors.name)
                swal(error.response.status.toString(), error.response.data.errors.name[0], 'error')
            else if (error.response.data.errors.username)
                swal(error.response.status.toString(), error.response.data.errors.username[0], 'error')
            else if (error.response.data.errors.description)
                swal(error.response.status.toString(), error.response.data.errors.description[0], 'error')
            else if (error.response.data.errors.price)
                swal(error.response.status.toString(), error.response.data.errors.price[0], 'error')
            else if (error.response.data.errors.photo_url)
                swal(error.response.status.toString(), error.response.data.errors.photo_url[0], 'error')
            else
                swal(error.response.status.toString(), 'Invalid data.', 'error')
        }
        else if (error.response.status == 500) {
            if (error.response.data.error)
                swal(error.response.status.toString(), error.response.data.error, 'error')
            else
                swal(error.response.status.toString(), 'We are expiriencing an internal problem.', 'error')
        }
    }
);

// navigation ward <=> middlewares
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.forVisitors)) {
        if (store.getters.isAuthenticated) {
            next({
                path: '/dashboard'
            })
        } else next()
    }
    else if (to.matched.some(record => record.meta.forManager)) {
        if (!store.getters.isManager) {
            next({
                path: '/dashboard'
            })
        } else next()
    }
    else if (to.matched.some(record => record.meta.forCook)) {
        if (!store.getters.isCook) {
            next({
                path: '/dashboard'
            })
        } else next()
    }
    else if (to.matched.some(record => record.meta.forWaiter)) {
        if (!store.getters.isWaiter) {
            next({
                path: '/dashboard'
            })
        } else next()
    }
    else if (to.matched.some(record => record.meta.forCashier)) {
        if (!store.getters.isCashier) {
            next({
                path: '/dashboard'
            })
        } else next()
    }
    else if (to.matched.some(record => record.meta.forAuth)) {
        if (!store.getters.isAuthenticated) {
            next({
                path: '/login'
            })
        } else next()
    } else next()
});

const app = new Vue({
    el: '#app',
    router: router,
    store,
    sockets: {
        // dispultado quando o socket e chamado
        connect() {
            console.log(`Socket connect with ID: ${this.$socket.id}`);
            if (store.state.user) {
                this.$socket.emit('user_enter', store.state.user);
            }
        },
        // managers
        managerMessage(dataFromServer){
            let sourceName = dataFromServer[1] === null ? 'Unknown': dataFromServer[1].name;
            this.$toasted.show('Message "' + dataFromServer[0] + '" sent from "' + sourceName + '"');        
        },

        managerMessage_unavailable(destUser){
            this.$toasted.error('User "' + destUser.name + '" is not available');       
        },
        managerMessage_sent(dataFromServer){
            this.$toasted.success('Message "' + dataFromServer[0] + '" was sent to "' + dataFromServer[1].name + '"');
        },
        // cooks
        cookMessage(dataFromServer){
            let sourceName = dataFromServer[1] === null ? 'Unknown': dataFromServer[1].name;
            this.$toasted.show('Message "' + dataFromServer[0] + '" sent from "' + sourceName + '"', {
                action : {
                    text : 'Go to Orders',
                    onClick : (e, toastObject) => {
                        this.$router.push("/orders")
                        toastObject.goAway(0);
                    }
                },
            });        
        },
        cookMessage_unavailable(destUser){
            this.$toasted.error('User "' + destUser.name + '" is not available');       
        },
        cookMessage_sent(dataFromServer){
            this.$toasted.success('Message "' + dataFromServer[0] + '" was sent to "' + dataFromServer[1].name + '"');
        },
        refresh_orders_assignment_update(dataFromServer){
            this.$store.dispatch('loadInPreparationUserOrders', this.$store.getters.getAuthUser.id);
            this.$store.dispatch('loadConfirmedOrders');
            
        },
        refresh_prepared_orders(order){
            this.$store.dispatch('loadInPreparationUserOrders', this.$store.getters.getAuthUser.id);
            this.$store.dispatch('loadConfirmedOrders');
        },
        // waiter of meal
        responsableWaiterMessage(dataFromServer){
            let sourceName = dataFromServer[1] === null ? 'Unknown': dataFromServer[1].name;
            this.$toasted.show('Message "' + dataFromServer[0] + '" sent from "' + sourceName + '"', {
                action : {
                    text : 'Go to Meals',
                    onClick : (e, toastObject) => {
                        this.$router.push("/mealsOfWaiter")
                        toastObject.goAway(0);
                    }
                },
            });        
        },
        responsableWaiterMessage_unavailable(destUser){
            this.$toasted.error('User "' + destUser.name + '" is not available');       
        },
        responsableWaiterMessage_sent(dataFromServer){
            this.$toasted.success('Message "' + dataFromServer[0] + '" was sent to "' + dataFromServer[1].name + '"');
        },
        // cashiers
        cashierMessage(dataFromServer){
            let sourceName = dataFromServer[1] === null ? 'Unknown': dataFromServer[1].name;
            this.$toasted.show('Message "' + dataFromServer[0] + '" sent from "' + sourceName + '"', {
                action : {
                    text : 'Go to Invoices',
                    onClick : (e, toastObject) => {
                        this.$router.push("/invoices")
                        toastObject.goAway(0);
                    }
                },
            });        
        },
        cashierMessage_unavailable(destUser){
            this.$toasted.error('User "' + destUser.name + '" is not available');       
        },
        cashierMessage_sent(dataFromServer){
            this.$toasted.success('Message "' + dataFromServer[0] + '" was sent to "' + dataFromServer[1].name + '"');
        },
        msg_from_server_type(dataFromServer){
            console.log('Receiving this message from Server: "' + dataFromServer + '"');
            let sourceName = dataFromServer[0] === null ? 'Unknown': dataFromServer[0];
            this.$toasted.show('Message "' + dataFromServer[1] + '" sent from "' + sourceName + '"');
        },
        refresh_invoices(data){
            this.$store.dispatch('loadPendingInvoices');
            this.$store.dispatch('loadPaidInvoices');
        },
    },
})
