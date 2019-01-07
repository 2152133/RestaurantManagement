<template>
    <div>
        <div class="jumbotron">
            <h1>List Orders</h1>
        </div>

        <orders-list :orders="inPreparationUserOrders" :meta="inPreparationUserOrdersMeta" :links="inPreparationUserOrdersLinks" @refreshOrders="refreshInPreparationUserOrders"></orders-list>
        
        <orders-list :orders="confirmedOrders" :meta="confirmedOrdersMeta" :links="confirmedOrdersLinks" @refreshOrders="refreshConfirmedOrders" ></orders-list>
            
        
        
    </div>         
</template>

<script>
    module.exports = {
        data: function() {
            return {
  
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
        },
        mounted() {
            this.loadConfirmedOrders();
            this.loadInPreparationUserOrders();
        }
    };
</script>
