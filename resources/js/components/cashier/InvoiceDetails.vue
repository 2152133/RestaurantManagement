<template>
    <div>
        <label>Date: {{invoice.date}}</label>
        <br/>
        <label>Table: {{invoice.table_number}}</label>
        <br/>
        <label>Responsible Waiter: {{invoice.responsible_waiter_name}}</label>
        <br/>
        <label>Items:</label>
        <br/>
        <label>Total: {{invoice.total_price}}</label>

        <button class="btn btn-danger btn-sm btn-block" @click="endViewingDetails()">Back</button>
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
            }
        }
    }
</script>