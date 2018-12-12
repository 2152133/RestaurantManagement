<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                  <th>Id</th>
                  <th>State</th>
                  <th>Item Name</th>
                  <th>Meal Id</th>
                  <th>Responsable cook</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>created_at</th>
                  <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order,index) in orders" :key="order.id">
                    <td>{{order.id}}</td>
                    <td>{{order.state}}</td>
                    <td>{{order.item_id}}</td>
                    <td>{{order.meal_id}}</td>
                    <td>{{order.responsible_cook_id}}</td>
                    <td>{{order.start}}</td>
                    <td>{{order.end}}</td>
                    <td>{{order.created_at.date}}</td>

                    <td>
                        <div v-if="order.state == 'confirmed'">
                            <button type="Submit" class="btn btn-primary btn-sm" @click="assignOrderToCook(order, index)">Assign to me</button>
                            <br></br>
                        </div>
                        
                        <button type="Submit" class="btn btn-danger btn-sm" @click="declareOrderAsPrepared(order, index)">Prepared</button>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>
</template>

<script>
    module.exports = {
        props: ["orders"],
        data: function() {
            return {
                
            }
        },
        methods: {
            mounted() {
                
            }
        }
    }
</script>