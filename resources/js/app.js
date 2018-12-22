
require('./bootstrap');

window.Vue = require('vue');

import store from './store/store';
import router from './routes/routes'
import VueSocketio from 'vue-socket.io';
import Toasted from 'vue-toasted';
 
Vue.use(Toasted, {
  position: 'bottom-center',
  duration: 5000,
  type: 'info',
});

Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://192.168.10.10:8080'
})); 



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
        console.log(error)
        //alert(error)
        // if (error.status == 404) {
        // }
        // else if (error.status == 500) {
        // }
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
    },
    methods: {
        sendGlobalMsg: function(){
            console.log('Sending to the server this message: "' + this.msgGlobalText + '"');
            if (store.state.user === null) {
                this.$socket.emit('msg_from_client', this.msgGlobalText);
            } else {
                this.$socket.emit('msg_from_client', this.msgGlobalText, store.state.user);
            }
            this.msgGlobalText = "";
        },
    },
    created() {
        console.log('-----');
        console.log(store.state.user);
    }
})