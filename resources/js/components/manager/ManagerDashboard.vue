<template>
        <div>
            <div class="jumbotron">
                <h1>{{title}}</h1>
            </div>
            
            <tables-list :tables="tables" :meta="tablesMeta" :links="tablesLinks" @editTable="editTable" @deleteTable="deleteTable" @refreshTables="refreshTables"></tables-list>
            <edit-table v-if="editingTable" :table="currentTable" @save="saveTable" @cancel="endEditingTable"></edit-table> 
            
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
                    this.newMeta = newMeta;
                    this.newLinks = newLinks;
                },
                saveTable(table){

                },
                endEditingTable(){
                    this.editingTable = false;
                    this.currentTable = {};
                }
            },
            mounted(){
                this.loadTables();
            }
        };
    </script>
    