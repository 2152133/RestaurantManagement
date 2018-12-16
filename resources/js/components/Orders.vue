<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        
        <orders-list :orders="userOrders"></orders-list>
        <orders-list :orders="orders" :user="currentUser" v-on:assign-to-cook="assignOrderToCook"></orders-list>
            
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
                currentOrder: {},
                currentUser: '52',
                orders: [],
                userOrders: [],
                changingOrder: {}
            }
        }
        ,
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
                axios.patch('/api/orders/' + order.id, {order: JSON.stringify(order), user: this.currentUser})
                    .then((response) => {
                        // handle success
                        this.orders = [];
                        this.userOrders = [];
                        this.loadConfirmedOrders();
                        this.loadUserOrders();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                    
            },
            loadUserOrders: function(){
                axios.get('/api/orders/fromCook/' + this.currentUser)
                    .then((response) => {
                        // handle success
                        this.userOrders = response.data.data;
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
                axios.get('/api/orders/all')
                    .then((response) => {
                        // handle success
                        this.orders = response.data.data;
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
        },
        mounted() {
            this.loadConfirmedOrders();
            this.loadUserOrders();
        }
    };
</script>
