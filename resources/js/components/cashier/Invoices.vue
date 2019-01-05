<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>

        <div class="alert" :class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
            <strong>@{{successMessage}}</strong>
            <strong>@{{failMessage}}</strong>
        </div>

        <invoices-list :invoices="getPendingInvoices" :meta="getPendingInvoicesMeta" :links="getPendingInvoicesLinks" @refreshInvoices="refreshPendingInvoices"></invoices-list>
        <invoices-list :invoices="getPaidInvoices" :meta="getPaidInvoicesMeta" :links="getPaidInvoicesLinks" @refreshInvoices="refreshPaidInvoices"></invoices-list>
            
            
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
            }
        }
        ,
        methods: {
            loadPendingInvoices: function(){
                this.$store.dispatch('loadPendingInvoices');
            },
            loadPaidInvoices: function(){
                this.$store.dispatch('loadPaidInvoices');
            },
            refreshPendingInvoices(newPendingInvoices, newMeta, newLinks){
                this.$store.commit('refreshPendingInvoices', {newPendingInvoices, newMeta, newLinks});
            },
            refreshPaidInvoices(newPaidInvoices, newMeta, newLinks){
                this.$store.commit('refreshPaidInvoices', {newPaidInvoices, newMeta, newLinks});
            },
        },
        computed: {
            getCurrentInvoice(){
                return this.$store.getters.currentInvoice;
            },
            getPendingInvoices(){
                return this.$store.getters.pendingInvoices;
            },
            getPendingInvoicesMeta(){
                return this.$store.getters.pendingInvoicesMeta;
            },
            getPendingInvoicesLinks(){
                return this.$store.getters.pendingInvoicesLinks;
            },
            getPaidInvoices(){
                return this.$store.getters.paidInvoices;
            },
            getPaidInvoicesMeta(){
                return this.$store.getters.paidInvoicesMeta;
            },
            getPaidInvoicesLinks(){
                return this.$store.getters.paidInvoicesLinks;
            },
        },
        mounted() {
            this.loadPendingInvoices();
            this.loadPaidInvoices();
        }
    };
</script>