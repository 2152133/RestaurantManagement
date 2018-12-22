
require('./bootstrap');

window.Vue = require('vue');

import store from './store/store';
import router from './routes/routes'

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
    store
})