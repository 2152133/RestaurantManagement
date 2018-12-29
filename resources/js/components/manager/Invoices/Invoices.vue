<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <invoices-list-manager @invoice-details-click="setCurrentInvoice"></invoices-list-manager>
        <invoice-details v-if="currentInvoice" :invoice="currentInvoice" @endViewingDetails="cleanCurrentInvoice"></invoice-details>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            title: 'Invoices',
            currentInvoice: null,
        }
    },
    methods: {
        setCurrentInvoice(invoice) {
            this.currentInvoice = invoice;
        },
        cleanCurrentInvoice() {
            this.currentInvoice = null;
        }
    }
}
</script>

