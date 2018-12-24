
require('./bootstrap');

window.Vue = require('vue');

import store from './store/store';
import router from './routes/routes'
import VueSocketio from 'vue-socket.io';
import Toasted from 'vue-toasted';
import swal from 'sweetalert';
import VeeValidate from 'vee-validate';
 
Vue.use(Toasted, {
    position: 'bottom-center',
    duration: 5000,
    type: 'info',
});

Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://192.168.10.10:8080'
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
        else if (error.status == 500) {
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
    data: {
        msgGlobalText: '',
        msgGlobalTextArea: '',
    },
    methods: {
        sendGlobalMsg(){
            console.log('Sending to the server this message: "' + this.msgGlobalText + '"');
            if (store.state.user === null) {
                this.$socket.emit('msg_from_client', this.msgGlobalText);
            } else {
                this.$socket.emit('msg_from_client', this.msgGlobalText, store.state.user);
            }
            this.msgGlobalText = "";
        },
    },
    sockets: {
        // dispultado quando o socket e chamado
        connect() {
            console.log(`Socket connect with ID: ${this.$socket.id}`);
            if (store.state.user) {
                this.$socket.emit('user_enter', store.state.user);
            }
        },
        msg_from_server(data) {
            this.msgGlobalTextArea = data + '\n' + this.msgGlobalTextArea;
        },
        // privateMessage(dataFromServer){
        //     let sourceName = dataFromServer[1] === null ? 'Unknown': dataFromServer[1].name;
        //     this.$toasted.show('Message "' + dataFromServer[0] + '" sent from "' + sourceName + '"');        
        // },
        privateMessage_unavailable(destUser){
            this.$toasted.error('User "' + destUser.name + '" is not available');       
        },
        privateMessage_sent(dataFromServer){
            this.$toasted.success('Message "' + dataFromServer[0] + '" was sent to "' + dataFromServer[1].name + '"');
        },
    },
    created() {
        //console.log('-----');
        //console.log(store.state.user);
    }
})