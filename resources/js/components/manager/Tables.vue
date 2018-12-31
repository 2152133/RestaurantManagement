<template>
        <div>
            <div class="jumbotron">
                <h1>{{title}}</h1>
            </div>
            
            <div class="alert" :class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
                <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
                <strong>@{{successMessage}}</strong>
                <strong>@{{failMessage}}</strong>
            </div>

            <tables-list :tables="tables" :meta="tablesMeta" :links="tablesLinks" @createTable="openCreateTable" @editTable="editTable" @deleteTable="deleteTable" @refreshTables="refreshTables"></tables-list>

        </div>         
</template>

<script>
    module.exports = {
        data: function() {
            return {
                title: 'Tables',
            }
        }
        ,
        methods: {
            loadTables: function(){
                this.$store.dispatch('loadTables');
            },
            editTable(table){
                this.$store.state.currentTable = table;
                this.$router.push({name: 'editTable', params: {table: this.currentTable, title: 'Edit table'}})
            },
            deleteTable(table){
                axios.delete('/api/tables/' + table.table_number)
                    .then((response) => {
                        // handle success
                        this.$store.state.tables = {}
                        this.loadTables();
                        console.log(response);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            refreshTables(newTables, newMeta, newLinks){
                this.$store.state.tables = newTables;
                this.$store.state.tablesMeta = newMeta;
                this.$store.state.tablesLinks = newLinks;
            },
            saveTable(table, newTableNumber){
                axios.patch('/api/tables/' + table.table_number, {table: JSON.stringify(table), newTableNumber: newTableNumber, user: this.currentUser})
                    .then((response) => {
                        // handle success
                        this.$store.state.tables = {}
                        this.loadTables();
                        console.log(response);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                endEditingTable();
            },
            endEditingTable(){
                this.$store.state.editingTable = false;
                this.$store.state.currentTable = {};
            },
            openCreateTable(){
                this.$store.state.creatingTable = true;
                this.$router.push({name: 'addTable', params: {table: this.currentTable, title: 'Add table'}});            },
            createTable(table, newTableNumber){
                axios.post('/api/tables/' + newTableNumber)
                    .then((response) => {
                        // handle success
                        this.$store.state.tables = {}
                        this.loadTables();
                        this.$store.state.creatingTable = false;
                        console.log(response);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            endCreatingTable(){
                this.$store.state.creatingTable = false;
            },

        },
        computed: {
            tables(){
                return this.$store.getters.tables;
            },
            currentTable(){
                return this.$store.getters.currentTable;
            },
            tablesMeta(){
                return this.$store.getters.tablesMeta;
            },
            tablesLinks(){
                return this.$store.getters.tablesLinks;
            },
            editingTable(){
                return this.$store.getters.editingTable;
            },
            creatingTable(){
                return this.$store.getters.creatingTable;
            },

        },
        mounted(){
            this.loadTables();
        }
    };
</script>
    