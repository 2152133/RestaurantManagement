<template>
        <div>
            <form @submit.prevent="" class="mb-3">
                <div class="form-group">
                    <label>Table Number:</label>
                    <input class="form-control" type="text" name="tableNumber" v-model="table.table_number">
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
                    
                }
            }
            ,
            methods: {
                save(){
                    this.$emit('save', this.table);
                },
                cancel(){
                    this.$emit('cancel', this.table);
                }
            },
        };
    </script>