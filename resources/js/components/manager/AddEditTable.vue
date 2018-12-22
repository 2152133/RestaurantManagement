<template>
        <div>
            <form @submit.prevent="" class="mb-3">
                <div class="form-group">
                    <label>Table Number:</label>
                    <input id="newTableNumber" class="form-control" type="text" name="tableNumber" :value="table.table_number">
                </div>
                
                <button class="btn btn-success" @click="save()">Save</button>
                <button class="btn btn-danger" @click="cancel()">Cancel</button>
            </form>
        </div>         
</template>

<script>
        module.exports = {
            props:['table'],
            data: function() {
                return {
                    newTableNumber: -1,
                }
            }
            ,
            methods: {
                save(){
                    this.newTableNumber = document.getElementById("newTableNumber").value;
                    console.log(this.newTableNumber);
                    this.$emit('save', this.table, this.newTableNumber);
                },
                cancel(){
                    this.$emit('cancel', this.table);
                }
            },
        };
    </script>