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
                    /*if(!(this.invoice.name && this.invoice.nif)){
                        alert("Nif and name required");
                        return;
                    }
                    if(/^[a-zA-Z\s]*$/.test(this.invoice.name) && /^([0-9]{9})$/.test(this.invoice.nif)){*/
                        axios.patch('/api/invoice/declarePaid', {invoice: JSON.stringify(this.getCurrentInvoice), user: this.$store.getters.getAuthUser.id})
                        .then((response) => {
                            // handle success
                            this.sendInvoicePaid();
                            this.$router.go(-1);
                            console.log(response);
                        })
                    //}
                    
                },
                    
                cancel(){
                    this.$router.go(-1);
                },

                sendInvoicePaid(){
                    this.$socket.emit('invoice_paid', this.getCurrentInvoice);
                },  
            },
            computed: {
                getCurrentInvoice(){
                    return this.$store.getters.currentInvoice;
                }
            },
        };
    </script>