<template>
        <div>
            <div class="jumbotron">
                <h1>{{title}}</h1>
            </div>

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
            props:['table', 'title'],
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
                    //this.$emit('save', this.table, this.newTableNumber);
                    axios.post('/api/tables/' + this.newTableNumber)
                    .then((response) => {
                        // handle success
                        this.$store.state.tables = {}
                        this.$store.dispatch('loadTables');
                        this.$router.go(-1);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                },
                cancel(){
                    this.$store.state.currentTable = {};
                    this.$router.go(-1);
                }
            },
        };
    </script>