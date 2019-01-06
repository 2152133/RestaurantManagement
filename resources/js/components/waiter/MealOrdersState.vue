<template>
    <div>
        <div class="jumbotron">
            <h1>Meal Orders state</h1>
        </div>

        <div class="alert" :class="{'alert-success':showSuccess,'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <strong>{{successMessage}}</strong>
            <strong>{{failMessage}}</strong>
        </div>

        <h3>Pending Orders</h3>
        <orders-list :orders="getPendingMealOrders" v-on:delete-order="deleteOrder"></orders-list>

        <h3>Confirmed Orders</h3>
        <orders-list :orders="getConfirmedMealOrders"></orders-list>

        <h3>Prepared Orders</h3>
        <orders-list :orders="getPreparedMealOrders" v-on:mark-delivered="markDelivered"></orders-list>
        
            
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
                setTimeout(this.hideSuccess, 3000);
            },
            markDelivered: function (order, index) {
                axios.put("/api/meals/" + order.id + "/markPreparedOrderAsDelivered")
                    .then(response => {
                        this.successMessage = "Success marking delivered";
                        this.showSuccess = true;
                        this.$store.commit('removeOrderFromPreparedMealOrders', index);
                        setTimeout(this.hideSuccess, 3000);
                    });
            },
            hideSuccess(){
                this.successMessage = "";
                this.showSuccess = false;
            },
            loadMealConfirmedOrders() {
                axios.get("/api/meals/" + this.$store.getters.currentMeal.id + "/confirmedOrders")
                    .then(response => {
                        this.$store.commit('setConfirmedMealOrders', response.data.data);
                    });
            },
            loadMealPreparedOrders() {
                axios.get("/api/meals/" + this.$store.getters.currentMeal.id + "/preparedOrders")
                    .then(response => {
                        this.$store.commit('setPreparedMealOrders', response.data.data);
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
            getPreparedMealOrders() {
                return this.$store.getters.preparedMealOrders;
            },
        },
        mounted() {
            this.loadMealConfirmedOrders();
            this.loadMealPreparedOrders();
        }
    };
</script>