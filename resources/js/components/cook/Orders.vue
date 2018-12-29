<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        
        <orders-list :orders="inPreparationUserOrders" :meta="inPreparationUserOrdersMeta" :links="inPreparationUserOrdersLinks" v-on:declare-order-as-prepared="declareOrderAsPrepared" @refreshOrders="refreshInPreparationUserOrders"></orders-list>
        
        <orders-list :orders="confirmedOrders" :meta="confirmedOrdersMeta" :links="confirmedOrdersLinks" v-on:assign-to-cook="assignOrderToCook" v-on:declare-order-as-prepared="declareOrderAsPrepared" @refreshOrders="refreshConfirmedOrders" ></orders-list>
            
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
                title: 'List Orders',   
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
            }
        },
        methods: {
            removeOrderFromArray: function(array, index){
                var order = array[index];
                array.splice(index, 1);
                console.log(order);
                return order;
            },
            addOrderToArray: function(array, order){
                array.push(order);
            },
            assignOrderToCook: function(order, index){
                axios.patch('/api/orders/' + order.id + '/assign', {userId: this.$store.getters.getAuthUser.id})
                    .then((response) => {
                        // handle success
                        this.$store.state.confirmedOrders = [];
                        this.$store.state.inPreparationUserOrders = [];
                        this.loadConfirmedOrders();
                        this.loadInPreparationUserOrders();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                    
            },
            declareOrderAsPrepared: function(order, index){
                axios.patch('/api/orders/' + order.id + '/prepared', {userId: this.$store.getters.getAuthUser.id})
                    .then((response) => {
                        // handle success
                        this.$store.state.confirmedOrders = [];
                        this.$store.state.inPreparationUserOrders = [];
                        this.loadConfirmedOrders();
                        this.loadInPreparationUserOrders();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            loadInPreparationUserOrders: function(){
                axios.get('/api/orders/inPreparation/fromCook/' + this.$store.getters.getAuthUser.id)
                    .then((response) => {
                        // handle success
                        this.$store.state.inPreparationUserOrders = response.data.data;
                        this.$store.state.inPreparationUserOrdersMeta = response.data.meta;
                        this.$store.state.inPreparationUserOrdersLinks = response.data.links;
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
            loadConfirmedOrders: function(){
                axios.get('/api/orders/confirmed')
                    .then((response) => {
                        // handle success
                        this.$store.state.confirmedOrders = response.data.data;
                        this.$store.state.confirmedOrdersMeta = response.data.meta;
                        this.$store.state.confirmedOrdersLinks = response.data.links;
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
            refreshConfirmedOrders(newConfirmedOrders, newConfirmedMeta, newConfirmedLinks){
                this.$store.state.confirmedOrders = newConfirmedOrders;
                this.$store.state.confirmedOrdersMeta = newConfirmedMeta;
                this.$store.state.confirmedOrdersLinks = newConfirmedLinks;
            },
            refreshInPreparationUserOrders(newInPreparationUserOrders, newInPreparationUserOrdersMeta, newInPreparationUserOrdersLinks){
                this.$store.state.inPreparationUserOrders = newInPreparationUserOrders;
                this.$store.state.inPreparationUserOrdersMeta = newInPreparationUserOrdersMeta;
                this.$store.state.inPreparationUserOrdersLinks = newInPreparationUserOrdersLinks;
            }
        },
        computed: {
            confirmedOrders(){
                return this.$store.getters.confirmedOrders;
            },
            confirmedOrdersMeta(){
                return this.$store.getters.confirmedOrdersMeta;
            },
            confirmedOrdersLinks(){
                return this.$store.getters.confirmedOrdersLinks;
            },
            inPreparationUserOrders(){
                return this.$store.getters.inPreparationUserOrders;
            },
            inPreparationUserOrdersMeta(){
                return this.$store.getters.inPreparationUserOrdersMeta;
            },
            inPreparationUserOrdersLinks(){
                return this.$store.getters.inPreparationUserOrdersLinks;
            },
            currentOrder(){
                return this.$store.getters.currentOrder;
            }
        },
        mounted() {
            this.loadConfirmedOrders();
            this.loadInPreparationUserOrders();
        }
    };
</script>
