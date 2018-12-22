<template>
        <div>
            <form @submit.prevent="" class="mb-3">
                <div class="form-group">
                    <label>Nif:</label>
                    <input class="form-control" type="text" name="nif" pattern="[0-9]{9}" title="9 numbers" v-model="invoice.nif">
                </div>
                
                <div class="form-group">
                    <label>Name:</label>
                    <input class="form-control" type="text" name="name" pattern="[a-zA-Z\s]*" title="Only letters and spaces" v-model="invoice.name">
                </div>
                
                <button class="btn btn-success" @click="declareInvoiceAsPaid()">Save</button>
                <button class="btn btn-danger" @click="cancel()">Cancel</button>
            </form>
        </div>         
</template>

<script>
        module.exports = {
            props:['invoice', 'index'],
            data: function() {
                return {
                    
                }
            }
            ,
            methods: {
                declareInvoiceAsPaid(){
                    if(!(this.invoice.name && this.invoice.nif)){
                        alert("Nif and name required");
                        return;
                    }
                    if(/^[a-zA-Z\s]*$/.test(this.invoice.name) && /^([0-9]{9})$/.test(this.invoice.nif)){
                        axios.patch('/api/invoice/declarePaid', {invoice: JSON.stringify(this.invoice), user: this.currentUser})
                        .then((response) => {
                            axios.get('/api/invoices/pending')
                                .then((response) => {
                                    // handle success
                                    $invoices = response.data.data;
                                    $meta = response.data.meta;
                                    $links = response.data.links;
                                    this.$emit('declareAsPaid', $invoices, $meta, $links);
                                    console.log(response);
                                })
                                .catch(function (error) {
                                    // handle error
                                    alert(error);
                                    console.log(error);
                                })
                                .then(function () {
                                    // always executed
                                });
                            
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                        .then(function () {
                            // always executed
                        });
                    }
                    
                    
                },
                cancel(){
                    this.$emit('cancelEditing');
                }
            },
        };
    </script>