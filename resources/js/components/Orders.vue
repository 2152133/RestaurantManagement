<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        
        <orders-list :orders="orders"></orders-list>
            
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
                orders: []
            }
        }
        ,
        methods: {
            loadOrders: function(){
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
            this.loadOrders();
        }
    };
</script>
