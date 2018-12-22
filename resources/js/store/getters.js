export default {
    getToken(state) {
        let token = localStorage.getItem('access_token')
        let tokenType = localStorage.getItem('token_type')
        let expiration = localStorage.getItem('expiration_time')

        if (!token || !tokenType || !expiration) {
            return null;
        }

        if (Date.now() > parseInt(expiration)) {
            state.token = "";
            state.tokenType = "";
            localStorage.removeItem('token_type')
            localStorage.removeItem('access_token')
            localStorage.removeItem('expiration_time')
            axios.defaults.headers.common.Authorization = undefined;
            return null;
        }

        return token;
    },
    getTokenType(state) {
        let tokenType = localStorage.getItem('token_type')
        
        if (!tokenType) {
            return null;
        }

        return tokenType;
    },
    getExpiration(state) {
        let expiration = localStorage.getItem('expiration_time')
        
        if (!expiration) {
            return null;
        }

        return expiration;
    },
    getAuthUser(state) {
        let user = localStorage.getItem('user')

        if (!user) {
            return null;
        }

        return JSON.parse(user);;
    },
    isAuthenticated(state) {
        return state.token && state.tokenType ? true:false;
    },
}