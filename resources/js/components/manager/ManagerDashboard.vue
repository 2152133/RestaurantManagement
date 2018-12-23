<template>
        <div>
            <div class="jumbotron">
                <h1>{{title}}</h1>
            </div>
            
            <tables-list v-if="!editingTable && !creatingTable" :tables="tables" :meta="tablesMeta" :links="tablesLinks" @createTable="openCreateTable" @editTable="editTable" @deleteTable="deleteTable" @refreshTables="refreshTables"></tables-list>
            <add-edit-table v-if="editingTable" :table="currentTable" @save="saveTable" @cancel="endEditingTable"></add-edit-table> 
            <add-edit-table v-if="creatingTable" :table="currentTable" @save="createTable" @cancel="endCreatingTable"></add-edit-table>
            
            <items-list :items="items" @edit-click="editItem" @delete-click="deleteItem" @message="childMessage" ref="itemsListRef"></items-list>
            <div class="alert" :class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
                <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
                <strong>@{{successMessage}}</strong>
                <strong>@{{failMessage}}</strong>
            </div>
            
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
                    tables:[],
                    currentTable:{},
                    tablesMeta:{},
                    tablesLinks:{},
                    editingTable: false,
                    creatingTable: false,
                    currentItem: null,
                    items: [],
                }
            }
            ,
            methods: {
                loadTables: function(){
                axios.get('/api/tables/all')
                        .then((response) => {
                            // handle success
                            this.tables = response.data.data;
                            this.tablesMeta = response.data.meta;
                            this.tablesLinks = response.data.links;
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
                    this.currentTable = table;
                    this.editingTable = true;
                },
                deleteTable(table){
                    axios.delete('/api/tables/' + table.table_number)
                        .then((response) => {
                            // handle success
                            this.tables = {}
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
                    this.tables = newTables;
                    this.tablesMeta = newMeta;
                    this.tablesLinks = newLinks;
                },
                saveTable(table, newTableNumber){
                    axios.patch('/api/tables/' + table.table_number, {table: JSON.stringify(table), newTableNumber: newTableNumber, user: this.currentUser})
                        .then((response) => {
                            // handle success
                            this.tables = {}
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
                    this.editingTable = false;
                    this.currentTable = {};
                },
                openCreateTable(){
                    this.creatingTable = true;
                },
                createTable(table, newTableNumber){
                    axios.post('/api/tables/' + newTableNumber)
                        .then((response) => {
                            // handle success
                            this.tables = {}
                            this.loadTables();
                            this.creatingTable = false;
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
                    this.creatingTable = false;
                },
                editItem(item){
	            this.currentItem = item;
	            this.showSuccess = false;
	        },
            deleteItem(item){
                axios.delete('api/item/' + item.id)
                .then(response => {
                    this.getItems();
                })
            },
            savedItem: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingitem = null;
                this.showSuccess = true;
                this.successMessage = 'Item Saved';
            },
            cancelEdit: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingitem = null;
                this.showSuccess = false;
            },
            getItems: function(){
                axios.get('api/items')
                .then(response=>{
                    this.items = response.data.data; 
                });
            },
            childMessage: function(message){
                this.showSuccess = true;
                this.successMessage = message;
            } 
            },
            mounted(){
                this.loadTables();
                this.getItems();
            }
        };
    </script>
    