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

            <tables-list v-if="!editingTable && !creatingTable" :tables="tables" :meta="tablesMeta" :links="tablesLinks" @createTable="openCreateTable" @editTable="editTable" @deleteTable="deleteTable" @refreshTables="refreshTables"></tables-list>
            <add-edit-table v-if="editingTable" :table="currentTable" @save="saveTable" @cancel="endEditingTable"></add-edit-table> 
            <add-edit-table v-if="creatingTable" :table="currentTable" @save="createTable" @cancel="endCreatingTable"></add-edit-table>
            
            <items-list :items="items" @edit-click="editItem" @delete-click="deleteItem" @message="childMessage" ref="itemsListRef"></items-list>
            
            
        </div>         
</template>

<script>
    module.exports = {
        data: function() {
            return {
                title: 'Management dashboard',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '', 
            }
        }
        ,
        methods: {
            loadTables: function(){
            axios.get('/api/tables/all')
                    .then((response) => {
                        // handle success
                        this.$store.state.tables = response.data.data;
                        this.$store.state.tablesMeta = response.data.meta;
                        this.$store.state.tablesLinks = response.data.links;
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
            editTable(table){
                this.$store.state.currentTable = table;
                this.$store.state.editingTable = true;
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
            },
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
            editItem(item){
            this.$store.state.currentItem = item;
            this.$store.state.showSuccess = false;
            },
            deleteItem(item){
                axios.delete('api/item/' + item.id)
                .then(response => {
                    this.getItems();
                })
            },
            savedItem: function(){
                this.$store.state.currentItem = null;
                this.$refs.itemsListRef.editingitem = null;
                this.showSuccess = true;
                this.successMessage = 'Item Saved';
            },
            cancelEdit: function(){
                this.$store.state.currentItem = null;
                this.$refs.itemsListRef.editingitem = null;
                this.showSuccess = false;
            },
            getItems: function(){
                axios.get('api/items')
                .then(response=>{
                    this.$store.state.items = response.data.data; 
                });
            },
            childMessage: function(message){
                this.showSuccess = true;
                this.successMessage = message;
            } 
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
            currentItem(){
                return this.$store.getters.currentItem;
            },
            items(){
                return this.$store.getters.items;
            },
        },
        mounted(){
            this.loadTables();
            this.getItems();
        }
        };
</script>
    