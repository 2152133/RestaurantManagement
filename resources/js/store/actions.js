export default {
    setAuthUser({commit}, data) {
        commit('setAuthUser', data);
    },
    loadInPreparationUserOrders(context, userId){
        axios.get('/api/orders/inPreparation/fromCook/' + userId)
            .then((response) => {
                // handle success
                console.log(response);
                var id = context.getters.getAuthUser.id;
                console.log(id);
                context.commit('assignResponseToInPreparationUserOrders', response);

            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    },
    loadConfirmedOrders(context){
        axios.get('/api/orders/confirmed')
            .then((response) => {
                // handle success
                context.commit('assignResponseToConfirmedOrders', response);
                console.log(response);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    },
}