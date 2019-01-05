<template>
    <div>
        <div id="receipt">
            <label>Date: {{getCurrentInvoice.date}}</label>
            <br/>
            <label>Table: {{getCurrentInvoice.table_number}}</label>
            <br/>
            <label>Responsible Waiter: {{ getCurrentInvoice.responsible_waiter ? getCurrentInvoice.responsible_waiter.name : " - NONE - " }}</label>
            <br/>
            <label>Name: {{ getCurrentInvoice.name ? getCurrentInvoice.name : " - NONE - " }}</label>
            <br/>
            <label>NIF: {{ getCurrentInvoice.nif ? getCurrentInvoice.nif : " - NONE - " }}</label>
            <br/>
            <table>
                <thead>
                    <th>Item</th>
                    <th>Sub-total</th>
                </thead>
                <tbody>
                    <tr v-for="item in getCurrentInvoice.items" :key="item.id">
                        <td>
                            <label>{{item.name}}</label>
                            <br/>
                            <label><span class="tab">{{item.quantity}} x {{item.price}}€</span></label>
                        </td> 
                        <td>
                            <label><span>{{item.price * item.quantity}}€</span></label>
                        </td>
                    </tr>
                </tbody>
                <tr><td><strong>Total:</strong></td><td><strong>{{getCurrentInvoice.total_price}}€</strong></td></tr>
            </table>
        </div>
        <button v-if="getCurrentInvoice.state == 'paid'" class="btn btn-primary btn-sm" @click="exportToPdf()">download</button>
        <br/>
        <br/>
        <button class="btn btn-danger btn-sm" @click="endViewingDetails()">Back</button>
        <br/>
        <br/>
        <br/>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                
            }
        },
        methods: {
            endViewingDetails(){
                this.$router.go(-1);
            },
            exportToPdf(){
                var doc = new jsPDF();
                doc.fromHTML($('#receipt').get(0), 20, 20);
                doc.save('receipt.pdf');
            }
        },
        computed:{
            getCurrentInvoice(){
                return this.$store.getters.currentInvoice;
            }
        }
    }
</script>

<style>
    .tab{
        margin-left: 5em;
    }
</style>