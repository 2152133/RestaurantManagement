<template>
    <div class="jumbotron">
    <h1>{{ subTitle }} {{ mealId }} <button class="btn btn-sm btn-danger" v-on:click.prevent="closeMealOrdersDetails()">Close</button></h1> 
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Order State</th>
            </tr>
        </thead>
        <tbody v-for="order in mealOrders" v-bind:key="order.id">
            <td>{{ item(order.item_id).name }}</td>
            <td>{{ item(order.item_id).type }}</td>
            <td>{{ item(order.item_id).price }}</td>
            <td>{{ order.state }}</td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    props: ['mealOrders', 'mealId'],
    data() {
        return {
            subTitle: 'Items of Meal: ',
            items: []
        }
    },
    methods: {
        getItems(id) {
            axios.get("/api/items/all")
            .then(response => {
                this.items = response.data
            })
        },
        item(id) {
            var item = []
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].id == id) {
                    item.name = this.items[i].name
                    item.type = this.items[i].type
                    item.price = this.items[i].price                    
                    break;
                }
            }
            return item != [] ? item : '- NONE -'
        },
        closeMealOrdersDetails() {
            this.$emit('close-meal-order-details-click');
        }
    },
    mounted() {
        this.getItems()
    }
}
</script>
