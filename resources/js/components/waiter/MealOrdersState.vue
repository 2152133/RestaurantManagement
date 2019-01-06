<template>
    <div>
        <div class="jumbotron">
            <h1>Meal Orders state</h1>
        </div>

        <h3>Confirmed Orders</h3>
        <orders-list :orders="getConfirmedMealOrders"></orders-list>

        <h3>Pending Orders</h3>
        <orders-list :orders="getPendingMealOrders" v-on:delete-order="deleteOrder"></orders-list>

        <div>
            <h3>Prepared Orders</h3>
            <div class="alert" :class="{'alert-success':showSuccess,'alert-danger':showFailure}" v-if="showSuccess || showFailure">
                <strong>{{successMessage}}</strong>
                <strong>{{failMessage}}</strong>
            </div>
            <orders-list :orders="getPreparedMealsOrders" v-on:mark-delivered="markDelivered"></orders-list>
        </div>
    </div>
</template>
<script>
    module.exports = {
        props: ['meal'],
        data() {
            return {
                successMessage: "",
                failMessage: "",
                showSuccess: false,
                showFailure: false,
            };
        },
        methods: {
            deleteOrder: function (order, index) {
                this.$store.commit('removeOrderFromPendingMealOrders', index);
                this.successMessage = "Order Deleted!";
                this.showSuccess = true;
            },
            markDelivered: function (order, index) {
                axios.put("/api/meals/" + order.id + "/markPreparedOrderAsDelivered")
                    .then(response => {
                        this.successMessage = "Success marking delivered";
                        this.showSuccess = true;
                        this.$store.commit('removeOrderFromPreparedMealOrders', index);
                    })
                    .catch(error => { });
            },
            loadMealConfirmedOrders() {
                axios.get("/api/meals/" + meal.id + "/confirmedOrders")
                    .then(response => {
                        this.$store.commit('setConfirmedMealOrders', response.data.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            loadMealPreparedOrders() {
                axios.get("/api/meals/" + meal.id + "/preparedOrders")
                    .then(response => {
                        this.$store.commit('setPreparedMealsOrders', response.data.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

        },
        computed: {
            getPendingMealOrders() {
                return this.$store.getters.pendingMealOrders;
            },
            getConfirmedMealOrders() {
                return this.$store.getters.confirmedMealOrders;
            },
            getPreparedMealsOrders() {
                return this.$store.getters.preparedMealsOrders;
            },
        },
        mounted() {
            loadMealConfirmedOrders();
            loadMealPreparedOrders();
        }
    };
</script>