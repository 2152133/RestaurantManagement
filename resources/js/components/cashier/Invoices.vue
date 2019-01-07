<template>
    <div>
        <div class="jumbotron">
            <h1>Pending Invoices</h1>
        </div>
        <h3>Pending Invoices</h3>
        <invoices-list :invoices="getPendingInvoices" :meta="getPendingInvoicesMeta" :links="getPendingInvoicesLinks" @refreshInvoices="refreshPendingInvoices"></invoices-list>
        <br/>
        <br/>
        <h3>Paid Invoices</h3>
        <invoices-list :invoices="getPaidInvoices" :meta="getPaidInvoicesMeta" :links="getPaidInvoicesLinks" @refreshInvoices="refreshPaidInvoices"></invoices-list>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
 

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