<template>
    <div>
        <h2>Add an order to a meal</h2>

        <label for="meals">Active meals that I'm responsible for</label>
        <select class="custom-select" v-model="selectedOptionMeal">
            <option disabled selected>-- Select an item --</option>
            <option v-for="meal in getUserMeals" v-bind:key="meal.id">{{meal.id}}</option>
        </select>

        <label for="items">Items</label>
        <select class="custom-select" v-model="selectedOptionItem">
            <option disabled selected>-- Select an item --</option>
            <option v-for="item in getAllItems" v-bind:key="item.id" :value="item.id">{{item.name}}</option>
        </select>

        <button type="button" class="btn btn-outline-success" v-on:click.prevent="createPendingMeal(selectedOptionMeal, selectedOptionItem)">Add order</button>
        <div class="alert" :class="{'alert-success':showSuccess,'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <strong>{{successMessage}}</strong>
            <strong>{{failMessage}}</strong>
        </div>

    </div>
</template>
<script>
module.exports = {
    data() {
        return {
            successMessage: "",
            failMessage: "",
            showSuccess: false,
            showFailure: false,
            selectedOptionItem: "Select an item",
            selectedOptionMeal: "Select a meal",
        };
    },
    methods: {
        createPendingMeal: function(meal_number, item_number) {
            var meal_order_timeout = meal_number;
            var item_number_timeout = item_number;

            this.$store.commit('setCounter', this.getCounter + 1);

            var datetimeToOrder = this.createDatetimeToOrder();

            this.$store.commit('createNewPendingMeal', {meal_number, item_number, datetimeToOrder});
            this.$store.commit('addOrderToPendingMealOrders', this.getCurrentMealOrder);

            var self = this;

            setTimeout(function() {
                self.createConfirmedOrder(meal_order_timeout, item_number_timeout);
                self.$store.commit('removeOrderFromPendingMealOrders', self.getPendingMealOrders.findIndex(o => o.id === self.counter));
            }, 5000);

            this.$router.go(-1);
        },
        createDatetimeToOrder(){
            var currentdate = new Date();
            var datetimeToOrder =
                currentdate.getFullYear() +
                "-" +
                (currentdate.getMonth() + 1) +
                "-" +
                currentdate.getDate() +
                " " +
                currentdate.getHours() +
                ":" +
                currentdate.getMinutes() +
                ":" +
                currentdate.getSeconds();
            return datetimeToOrder;
        },
        createConfirmedOrder: function(meal_id, item_number) {
            if (!isNaN(this.getCurrentMealOrder.id)) {
                axios.post("/api/meal/addOrder/" + meal_id + "/" + item_number)
                .then(response => {
                    console.log('createConfirmedOrder request:');
                    console.log(response);
                    this.successMessage = "Success creating order!";
                    this.showSuccess = true;
                })
                .catch(error => {
                    console.log('createConfirmedOrder error:');
                    console.log(error);
                    this.showFailure = true;
                    this.failMessage = "Fail";
                });
            } else {
                return;
            }   
        },
    },
    computed: {
        getCurrentUser() {
            return this.$store.getters.getAuthUser;
        },
        getUserMeals() {
            return this.$store.getters.userMeals;
        },
        getCurrentMeal() {
            return this.$store.getters.currentMeal;
        },
        getAllMealOrders() {
            return this.$store.getters.allMealOrders;
        },
        getPendingMealOrders() {
            return this.$store.getters.pendingMealOrders;
        },
        getConfirmedMealOrders() {
            return this.$store.getters.confirmedMealOrders;
        },
        getPreparedMealsOrders() {
            return this.$store.getters.preparedMealsOrders;
        },
        getNotDeliveredOrdersOfMeal() {
            return this.$store.getters.notDeliveredOrdersOfMeal;
        },
        getAllItems() {
            return this.$store.getters.allItems;
        },
        getMealDetails() {
            return this.$store.getters.mealDetails;
        },
        getCurrentMealOrder() {
            return this.$store.getters.currentMealOrder;
        },
        getCounter(){
            return this.$store.getters.counter;
        }
    },
    mounted() {

    }
};
</script>