<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        
        <invoices-list :invoices="pendingInvoices" :meta="pendingInvoicesMeta" :links="pendingInvoicesLinks" @refreshInvoices="refreshPendingInvoices"></orders-list>
            
        <div class="alert" :class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
            <strong>@{{successMessage}}</strong>
            <strong>@{{failMessage}}</strong>
        </div>
        
    </div>         
</template>

<script>
    module.exports = {
        data: function() {
            return {
                title: 'Pending Invoices',   
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentInvoice: {},
                currentUser: '52',
                pendingInvoices: [],
                pendingInvoicesMeta:[],
                pendingInvoicesLinks:[],
            }
        }
        ,
        methods: {
            loadPendingInvoices: function(){
                axios.get('/api/invoices/pending/')
                    .then((response) => {
                        // handle success
                        this.pendingInvoices = response.data.data;
                        this.pendingInvoicesMeta = response.data.meta;
                        this.pendingInvoicesLinks = response.data.links;
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
            refreshPendingInvoices(newPendingInvoices, newMeta, newLinks){
                this.pendingInvoices = newPendingInvoices;
                this.ordersMeta = newMeta;
                this.ordersLinks = newLinks;
            },
        },
        mounted() {
            this.loadPendingInvoices();
        }
    };
</script>