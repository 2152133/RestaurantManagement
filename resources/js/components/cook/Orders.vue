<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        
        <h3>In Preparation</h3>
        <orders-list :orders="inPreparationUserOrders" :meta="inPreparationUserOrdersMeta" :links="inPreparationUserOrdersLinks" @refreshOrders="refreshInPreparationUserOrders"></orders-list>
        <br/>
        <br/>
        <h3>Confirmed</h3>
        <orders-list :orders="confirmedOrders" :meta="confirmedOrdersMeta" :links="confirmedOrdersLinks" @refreshOrders="refreshConfirmedOrders" ></orders-list>
            
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
            loadInPreparationUserOrders: function(){
                this.$store.dispatch('loadInPreparationUserOrders', this.$store.getters.getAuthUser.id);
            },
            loadConfirmedOrders: function(){
                this.$store.dispatch('loadConfirmedOrders');
            },
            refreshConfirmedOrders(newConfirmedOrders, newConfirmedMeta, newConfirmedLinks){
                this.$store.commit('refreshConfirmedOrders', {newConfirmedOrders, newConfirmedMeta, newConfirmedLinks});
            },
            refreshInPreparationUserOrders(newInPreparationUserOrders, newInPreparationUserOrdersMeta, newInPreparationUserOrdersLinks){
                this.$store.commit('refreshInPreparationUserOrders', {newInPreparationUserOrders, newInPreparationUserOrdersMeta, newInPreparationUserOrdersLinks});
            },
            
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
