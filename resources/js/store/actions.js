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
    assignOrderToCook(context, payload){
        console.log("2: " + payload.userId);
        axios.patch('/api/orders/' + payload.orderId + '/assignTo/' + payload.userId)
            .then((response) => {
                // handle success
                context.commit('cleanOrdersArrays');
                context.dispatch('loadConfirmedOrders');
                context.dispatch('loadInPreparationUserOrders', payload.userId);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    },
    declareOrderAsPrepared(context, payload){
        axios.patch('/api/orders/' + payload.orderId + '/preparedBy/' + payload.userId)
            .then((response) => {
                // handle success
                context.commit('cleanOrdersArrays');
                context.dispatch('loadConfirmedOrders');
                context.dispatch('loadInPreparationUserOrders', payload.userId);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    },
    loadTables(context){
        axios.get('/api/tables/all')
                .then((response) => {
                    // handle success
                    context.commit('refreshTablesPagination', response);
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