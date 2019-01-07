<template>
        <div>
            <form @submit.prevent="" class="mb-3">
                <div class="form-group">
                    <label>Nif:</label>
                    <input class="form-control" type="number" name="nif" title="9 numbers" v-model="getCurrentInvoice.nif" v-validate="'required|numeric|max:9|min:9'">
                </div>
                <div class="help-block alert alert-danger"
                    v-show="errors.has('nif')">
                    {{ errors.first('nif')}}
                </div>
                <div class="form-group">
                    <label>Name:</label>
                    <input class="form-control" type="text" name="name" title="Only letters and spaces" v-model="getCurrentInvoice.name" v-validate="'required'">
                </div>
                <div class="help-block alert alert-danger"
                    v-show="errors.has('name')">
                    {{ errors.first('name')}}
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
                    this.$validator.validateAll().then((result) => {
                        if(result) {
                            axios.patch('/api/invoice/declarePaid', {invoice: JSON.stringify(this.getCurrentInvoice), user: this.$store.getters.getAuthUser.id})
                            .then((response) => {
                                // handle success
                                this.sendInvoicePaid();
                                this.$router.go(-1);
                                console.log(response);
                            })
                        }
                    })
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