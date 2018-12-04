<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                  <th>Id</th>
                  <th>State</th>
                  <th>Item Name</th>
                  <th>Responsable cook</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order,index) in orders" :key="order.id">
                    <td>{{order.id}}</td>
                    <td>{{order.state}}</td>
                    <td>{{order.item_id}}</td>
                    <td>{{order.responsable_cook_id}}</td>
                    <td>{{order.start}}</td>
                    <td>{{order.end}}</td>
                    <td>
                        <button type="Submit" class="btn btn-primary btn-sm" @click="editOrder(order)">Edit</button>
                        <button type="Submit" class="btn btn-danger btn-sm" @click="deleteOrder(order, index)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["orders"],
    data: function() {
        return {
            
        }
    },
    methods: {
        editOrder(order, index){

        },
        deleteUser(user, index){

        },
        mounted() {
            
        }
    }
}
</script>