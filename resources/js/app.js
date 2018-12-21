
require('./bootstrap');

window.Vue = require('vue');


import store from './store/store';
import router from './routes/routes'



// navigation ward
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
    created() {
        store.state.user = store.getters.getUser
        store.state.token = store.getters.getToken
    },
})


