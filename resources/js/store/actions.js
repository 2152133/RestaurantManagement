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
    declareInvoiceAsPaid(context, payload){
        /*if(!(this.invoice.name && this.invoice.nif)){
            alert("Nif and name required");
            return;
        }
        if(/^[a-zA-Z\s]*$/.test(this.invoice.name) && /^([0-9]{9})$/.test(this.invoice.nif)){*/
            axios.patch('/api/invoice/declarePaid', {invoice: JSON.stringify(payload.invoice), user: payload.userId})
            .then((response) => {
                axios.get('/api/invoices/pending')
                    .then((response) => {
                        // handle success
                        context.dispatch('loadPendingInvoices');
                        context.dispatch('loadPaidInvoices');
                        console.log(response);
                    })
                    .catch(function (error) {
                        // handle error
                        alert(error);
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
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