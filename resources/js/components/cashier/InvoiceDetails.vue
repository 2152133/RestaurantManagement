<template>
    <div>
        <div id="receipt">
            <label>Date: {{invoice.date}}</label>
            <br/>
            <label>Table: {{invoice.table_number}}</label>
            <br/>
            <label>Responsible Waiter: {{invoice.responsible_waiter_name}}</label>
            <br/>
            <table>
                <thead>
                    <th>Item</th>
                    <th>Sub-total</th>
                </thead>
                <tbody>
                    <tr v-for="item in invoice.items" :key="item.id">
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
                <tr><td><strong>Total:</strong></td><td><strong>{{invoice.total_price}}€</strong></td></tr>
            </table>
        </div>
        <button v-if="invoice.state == 'paid'" class="btn btn-primary btn-sm" @click="exportToPdf()">download</button>
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
        props: ["invoice","user"],
        data: function() {
            return {
                
            }
        },
        methods: {
            endViewingDetails(){
                this.$emit('endViewingDetails');
            },
            exportToPdf(){
                var doc = new jsPDF();
                doc.fromHTML($('#receipt').get(0), 20, 20);
                doc.save('receipt.pdf');
            }
        }
    }
</script>

<style>
    .tab{
        margin-left: 5em;
    }
</style>