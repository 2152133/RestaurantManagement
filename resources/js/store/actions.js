export default {
    setAuthUser({commit}, data) {
        commit('setAuthUser', data);
    },

    //--------------------Orders---------------------------------
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
    /*assignOrderToCook(context, payload){
        axios.patch('/api/orders/' + payload.orderId + '/assignTo/' + payload.userId)
            .then((response) => {
                // handle success
                context.commit('cleanOrdersArrays');
                context.dispatch('loadConfirmedOrders');
                context.dispatch('loadInPreparationUserOrders', payload.userId);
            })
    },*/
    /*declareOrderAsPrepared(context, payload){
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
    },*/


    //----------------------Invoices---------------------------------------
    loadPendingInvoices(context){
        axios.get('/api/invoices/pending/')
            .then((response) => {
                // handle success
                context.commit('setPendingInvoices', response.data.data);
                context.commit('setPendingInvoicesMeta', response.data.meta);
                context.commit('setPendingInvoicesLinks', response.data.links);
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
    loadPaidInvoices(context){
        axios.get('/api/invoices/paid/')
            .then((response) => {
                // handle success
                context.commit('setPaidInvoices', response.data.data);
                context.commit('setPaidInvoicesMeta', response.data.meta);
                context.commit('setPaidInvoicesLinks', response.data.links);
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


    //--------------------------Meals-------------------------------------
    loadMealConfirmedOrders(context) {
        if(context.getters.currentMeal.id){
            axios.get("/api/meals/" + context.getters.currentMeal.id + "/confirmedOrders")
            .then(response => {
                context.commit('setConfirmedMealOrders', response.data.data);
                context.commit('setConfirmedMealOrdersMeta', response.data.meta);
                context.commit('setConfirmedMealOrdersLinks', response.data.links);
            });
        }
        
    },


    //------------------------Tables--------------------------------------
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