<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <edit-nif-name v-if="editingNifName" :invoice="currentInvoice" @declareAsPaid="endEditingSave" @cancelEditing="endEditingCancel"></edit-nif-name>
        <invoice-details v-if="viewingDetails" :invoice="currentInvoice" @endViewingDetails="endViewingDetails"></invoice-details>
        <div v-if="!editingNifName && !viewingDetails">
            <invoices-list :invoices="pendingInvoices" :meta="pendingInvoicesMeta" :links="pendingInvoicesLinks" @refreshInvoices="refreshPendingInvoices" @declareAsPaid="loadPendingInvoices" @fillNifName="fillNifName" @seeDetails="seeDetails"></invoices-list>
            <invoices-list :invoices="paidInvoices" :meta="paidInvoicesMeta" :links="paidInvoicesLinks" @refreshInvoices="refreshPaidInvoices" @seeDetails="seeDetails"></invoices-list>
            
            <div class="alert" :class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
                <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
                <strong>@{{successMessage}}</strong>
                <strong>@{{failMessage}}</strong>
            </div>
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
                paidInvoices: [],
                paidInvoicesMeta:[],
                paidInvoicesLinks:[],
                editingNifName: false,
                viewingDetails: false,
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
                    axios.get('/api/invoices/paid/')
                    .then((response) => {
                        // handle success
                        this.paidInvoices = response.data.data;
                        this.paidInvoicesMeta = response.data.meta;
                        this.paidInvoicesLinks = response.data.links;
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
                this.pendingInvoicesMeta = newMeta;
                this.pendingInvoicesLinks = newLinks;
            },
            refreshPaidInvoices(newPendingInvoices, newMeta, newLinks){
                this.paidInvoices = newPendingInvoices;
                this.paidInvoicesMeta = newMeta;
                this.paidInvoicesLinks = newLinks;
            },
            fillNifName(invoice){
                if(invoice.state == 'pending'){
                    this.editingNifName = true;
                    this.currentInvoice = invoice;
                }
                
            },
            endEditingSave($pendingInvoices, $meta, $links){
                this.invoices = $pendingInvoices;
                this.meta = $meta;
                this.links = $links;
                this.editingNifName = false;
                this.currentInvoice = {};
            },
            endEditingCancel(){
                this.editingNifName = false;
                this.currentInvoice = {};
            },
            seeDetails(invoice){
                this.viewingDetails = true;
                this.currentInvoice = invoice;
            },
            endViewingDetails(){
                this.viewingDetails = false;
                this.currentInvoice = {};
            }
        },
        mounted() {
            this.loadPendingInvoices();
        }
    };
</script>