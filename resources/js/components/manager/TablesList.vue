<template>
        <div>
            <h3>Tables</h3>
            <pagination :objects="tables" :meta="meta" :links="links" @refreshObjects="refreshTables"></pagination>
            <table class="table">
                <thead>
                    <tr>
                      <th>Table Number</th>
                      <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(table,index) in tables" :key="table.table_number">
                        <td>{{table.table_number}}</td>
                        <td>
                            <!--<button type="Submit" class="btn btn-primary btn-sm btn-block" @click="editTable(table, index)">Edit</button>-->
                            <button type="Submit" class="btn btn-danger btn-sm btn-block" @click="deleteTable(table, index)">Delete</button>
                        </td>
                    </tr>
                </tbody>
                <button class="btn btn-success" @click="create()">New Table</button>
            </table>
            <br/>
            <br/>
            <br/>
        </div>
    </template>
    
    <script>
        module.exports = {
            props: ["tables", "meta", "links", "user"],
            data: function() {
                return {
                    
                }
            },
            methods: {
                create(){
                    this.$emit('createTable');
                },
                editTable(table){
                    this.$emit('editTable', table);
                },
                deleteTable(table){
                    this.$emit('deleteTable', table);
                },
                refreshTables(tables, meta, links){
                    this.$emit('refreshTables', tables, meta, links);
                }
            }
        }
    </script>