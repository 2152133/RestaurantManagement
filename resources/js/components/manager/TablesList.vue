<template>
        <div>
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
                            <button type="Submit" class="btn btn-primary btn-sm btn-block" @click="editTable(table, index)">Edit</button>
                            <button type="Submit" class="btn btn-danger btn-sm btn-block" @click="deleteTable(table, index)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
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