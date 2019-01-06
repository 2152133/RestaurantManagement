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
        <orders-list :orders="getPendingMealOrders" :meta="getPendingMealOrdersMeta" :links="getPendingMealOrdersLinks" v-on:delete-order="deleteOrder" @refreshOrders="refreshPendingMealOrders"></orders-list>

        <h3>Confirmed Orders</h3>
        <orders-list :orders="getConfirmedMealOrders" :meta="getConfirmedMealOrdersMeta" :links="getConfirmedMealOrdersLinks" @refreshOrders="refreshConfirmedMealOrders"></orders-list>

        <h3>Prepared Orders</h3>
        <orders-list :orders="getPreparedMealOrders" :meta="getPreparedMealOrdersMeta" :links="getPreparedMealOrdersLinks" v-on:mark-delivered="markDelivered" @refreshOrders="refreshPreparedMealOrders"></orders-list>
        
            
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
                        this.$store.commit('setConfirmedMealOrdersMeta', response.data.meta);
                        this.$store.commit('setConfirmedMealOrdersLinks', response.data.links);
                    });
            },
            loadMealPreparedOrders() {
                axios.get("/api/meals/" + this.$store.getters.currentMeal.id + "/preparedOrders")
                    .then(response => {
                        this.$store.commit('setPreparedMealOrders', response.data.data);
                        this.$store.commit('setPreparedMealOrdersMeta', response.data.meta);
                        this.$store.commit('setPreparedMealOrdersLinks', response.data.links);
                    });
            },
            refreshPendingMealOrders(orders, meta, links){
                this.$store.commit('setPendingMealOrders', orders);
                this.$store.commit('setPendingMealOrdersMeta', meta);
                this.$store.commit('setPendingMealOrdersLinks', links);
            },
            refreshConfirmedMealOrders(orders, meta, links){
                this.$store.commit('setConfirmedMealOrders', orders);
                this.$store.commit('setConfirmedMealOrdersMeta', meta);
                this.$store.commit('setConfirmedMealOrdersLinks', links);
            },
            refreshPreparedMealOrders(orders, meta, links){
                this.$store.commit('setPreparedMealOrders', orders);
                this.$store.commit('setPreparedMealOrdersMeta', meta);
                this.$store.commit('setPreparedMealOrdersLinks', links);
            },

        },
        computed: {
            getPendingMealOrders() {
                return this.$store.getters.pendingMealOrders;
            },
            getPendingMealOrdersMeta() {
                return this.$store.getters.pendingMealOrdersMeta;
            },
            getPendingMealOrdersLinks() {
                return this.$store.getters.pendingMealOrdersLinks;
            },
            getConfirmedMealOrders() {
                return this.$store.getters.confirmedMealOrders;
            },
            getConfirmedMealOrdersMeta() {
                return this.$store.getters.confirmedMealOrdersMeta;
            },
            getConfirmedMealOrdersLinks() {
                return this.$store.getters.confirmedMealOrdersLinks;
            },
            getPreparedMealOrders() {
                return this.$store.getters.preparedMealOrders;
            },
            getPreparedMealOrdersMeta() {
                return this.$store.getters.preparedMealOrdersMeta;
            },
            getPreparedMealOrdersLinks() {
                return this.$store.getters.preparedMealOrdersLinks;
            },
        },
        mounted() {
            this.loadMealConfirmedOrders();
            this.loadMealPreparedOrders();
        }
    };
</script>