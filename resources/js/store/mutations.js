export default {
    setToken(state, {token, expiration}) {
        state.token = token;
        localStorage.setItem('token', token)
        localStorage.setItem('expiration', expiration)
        axios.defaults.headers.common.Authorization = "Bearer " + token;
    },
    clearToken(state) {
        state.token = "";
        localStorage.removeItem('token')
        localStorage.removeItem('expiration')
        axios.defaults.headers.common.Authorization = undefined;
    },
    setUser(state, user) {
        state.user =  user;
        localStorage.setItem('user', JSON.stringify(user));
    },
    clearUser(state) {
        state.user = null;
        localStorage.removeItem('user');
    },
    clearUserAndToken(state) {
        state.user = null;
        state.token = "";
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('expiration')
        axios.defaults.headers.common.Authorization = undefined;
    },
}