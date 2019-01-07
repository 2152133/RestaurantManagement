<template>
    <div>
        <div class="jumbotron">
            <h2>Add an order to a meal</h2>
        </div>
        <div class="alert" :class="{'alert-success':showSuccess,'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <strong>{{successMessage}}</strong>
            <strong>{{failMessage}}</strong>
        </div>


        <label>Active meals that I'm responsible for</label>
        <select class="custom-select" v-model="selectedOptionMeal">
            <option disabled selected>-- Select an item --</option>
            <option v-for="meal in getUserMeals" v-bind:key="meal.id">{{meal.id}}</option>
        </select>


        <label>Items</label>
        <select class="custom-select" v-model="selectedOptionItem">
            <option disabled selected>-- Select an item --</option>
            <option v-for="item in getAllItems" v-bind:key="item.id" :value="item">{{item.name}}</option>
        </select>


        <button type="button" class="btn btn-outline-success" v-on:click.prevent="createPendingMeal(selectedOptionMeal, selectedOptionItem)">Add order</button>

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
                selectedOptionItem: null,
                selectedOptionMeal: null,
            };
        },
        methods: {
            createPendingMeal: function (mealId, item) {
                var mealId_timeout = mealId;
                var item_timeout = item;

                this.$store.commit('setCounter', this.getCounter + 1);

                var datetimeToOrder = this.createDatetimeToOrder();

                this.$store.commit('createNewPendingMeal', { mealId, item, datetimeToOrder });
                this.$store.commit('addOrderToPendingMealOrders', this.getCurrentMealOrder);

                var self = this;

                setTimeout(function () {
                    self.createConfirmedOrder(mealId_timeout, item_timeout.id);
                    self.$store.commit('removeOrderFromPendingMealOrders', self.getPendingMealOrders.findIndex(o => o.id === self.counter));
                    
                }, 5000);
                
                this.$router.go(-1);
            },
            sendOrderConfirmed() {
                this.$socket.emit('order_confirmed');
            },
            createDatetimeToOrder() {
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
            createConfirmedOrder: function (meal_id, item_number) {
                if (!isNaN(this.getCurrentMealOrder.id)) {
                    axios.post("/api/meal/addOrder/" + meal_id + "/" + item_number)
                        .then(response => {
                            console.log('createConfirmedOrder request:');
                            console.log(response);
                            this.successMessage = "Success creating order!";
                            this.showSuccess = true;
                            this.sendOrderConfirmed();
                            let msg = 'New Order of item ' + item_number + '!'
                            this.$socket.emit('msg_from_client_type_waiter', msg, this.$store.getters.getAuthUser)
                
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
            getUserMeals() {
                return this.$store.getters.userMeals;
            },
            getPendingMealOrders() {
                return this.$store.getters.pendingMealOrders;
            },
            getAllItems() {
                return this.$store.getters.allItems;
            },
            getCurrentMealOrder() {
                return this.$store.getters.currentMealOrder;
            },
            getCounter() {
                return this.$store.getters.counter;
            }
        },
        mounted() {

        }
    };
</script>