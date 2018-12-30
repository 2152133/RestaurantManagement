<template>
    <div>
        <pagination :objects="invoices" :meta="meta" :links="links" @refreshObjects="refreshInvoices"></pagination>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>State</th>
                    <th>Meal</th>
                    <th>Table</th>
                    <th>Waiter Name</th>
                    <th>nif</th>
                    <th>name</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="invoice in invoices" :key="invoice.id">
                    <td>{{invoice.id}}</td>
                    <td>{{invoice.state}}</td>
                    <td>{{invoice.meal_id}}</td>
                    <td>{{invoice.table_number}}</td>
                    <td>{{invoice.responsible_waiter ? invoice.responsible_waiter.name : " - NONE - "}}</td>
                    <td>{{invoice.nif}}</td>
                    <td>{{invoice.name}}</td>
                    <td>{{invoice.total_price}} €</td>
                    <td>{{invoice.created_at.date}}</td>
                    <td>
                        <button v-if="invoice.state == 'pending'" class="btn btn-danger btn-sm btn-block" @click="fillNifName(invoice)">Paid</button>
                        <button class="btn btn-danger btn-sm btn-block" @click="seeDetails(invoice)">Details</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    module.exports = {
        props: ["invoices", "meta", "links", "user"],
        data: function() {
            return {
                
                
                currentInvoice: {},
            }
        },
        methods: {
            refreshInvoices(invoices, meta, links){
                this.$emit('refreshInvoices', invoices, meta, links);
            },
            fillNifName(invoice){
                this.$emit('fillNifName', invoice);
            },
            seeDetails(invoice){
                this.$emit('seeDetails', invoice);
            }
        }
    }
</script>