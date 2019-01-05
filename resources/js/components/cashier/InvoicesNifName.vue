<template>
        <div>
            <form @submit.prevent="" class="mb-3">
                <div class="form-group">
                    <label>Nif:</label>
                    <input class="form-control" type="text" name="nif" pattern="[0-9]{9}" title="9 numbers" v-model="getCurrentInvoice.nif">
                </div>
                
                <div class="form-group">
                    <label>Name:</label>
                    <input class="form-control" type="text" name="name" pattern="[a-zA-Z\s]*" title="Only letters and spaces" v-model="getCurrentInvoice.name">
                </div>
                
                <button class="btn btn-success" @click="declareInvoiceAsPaid()">Save</button>
                <button class="btn btn-danger" @click="cancel()">Cancel</button>
            </form>
        </div>         
</template>

<script>
        module.exports = {
            data: function() {
                return {
                    
                }
            },
            methods: {
                declareInvoiceAsPaid(){
                    this.$store.dispatch('declareInvoiceAsPaid', {invoice: this.getCurrentInvoice, userId: this.$store.getters.getAuthUser.id})
                    this.$router.go(-1);
                },
                    
                cancel(){
                    this.$router.go(-1);
                },
            },
            computed: {
                getCurrentInvoice(){
                    return this.$store.getters.currentInvoice;
                }
            },
        };
    </script>