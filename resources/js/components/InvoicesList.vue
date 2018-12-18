<template>
    <div>
        <pagination :objects="invoices" :meta="meta" :links="links" @refreshObjects="refreshInvoices"></pagination>
        <table class="table">
            <thead>
                <tr>
                  <th>Id</th>
                  <th>State</th>
                  <th>Meal Id</th>
                  <th>Table Number</th>
                  <th>responsible_waiter_id</th>
                  <th>responsible_waiter_name</th>
                  <th>Total Price</th>
                  <th>created_at</th>
                  <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(invoice,index) in invoices" :key="invoice.id">
                    <td>{{invoice.id}}</td>
                    <td>{{invoice.state}}</td>
                    <td>{{invoice.meal_id}}</td>
                    <td>{{invoice.table_number}}</td>
                    <td>{{invoice.responsible_waiter_id}}</td>
                    <td>{{invoice.responsible_waiter_name}}</td>
                    <td>{{invoice.total_price}}</td>
                    <td>{{invoice.created_at.date}}</td>
                    <td></td>
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
                
            }
        },
        methods: {
            refreshInvoices(invoices, meta, links){
                this.$emit('refreshInvoices', invoices, meta, links);
            }
        }
    }
</script>