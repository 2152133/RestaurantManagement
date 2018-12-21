export default {
    getToken(state) {
        let token = localStorage.getItem('token')
        let expiration = localStorage.getItem('expiration')

        if (!token || !expiration) {
            return null;
        }

        if (Date.now() > parseInt(expiration)) {
            state.token = "";
            localStorage.removeItem('token')
            localStorage.removeItem('expiration')
            axios.defaults.headers.common.Authorization = undefined;
            return null;
        }

        return token;
    },
    getUser(state) {
        let user = localStorage.getItem('user')

        if (!user) {
            return null;
        }

        return user;
    },
    isAuthenticated(state) {
        return state.token && state.user ? true:false;
    },
}