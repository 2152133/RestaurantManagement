<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <meals-list-manager @meal-order-details-click="setCurrentMealOrders"></meals-list-manager>
        <meal-details-list @close-meal-order-details-click="cleanCurrentMealOrders" :mealOrders="currentMealOrders" :mealId="mealId" v-if="currentMealOrders && mealId"></meal-details-list>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            status: 0,
            title: 'Meals',
            currentMealOrders: null,
            mealId: 0,
        }
    },
    methods: {
        setCurrentMealOrders(mealOrders, mealId) {
            this.currentMealOrders = mealOrders;
            this.mealId = mealId
        },
        cleanCurrentMealOrders() {
            this.currentMealOrders = null;
            this.mealId = 0
        }
    }
}
</script>

