<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>
    <meals-list
      :meals="usersMeals"
      v-on:show-ordersOfMeal="showOrdersOfMeal"
      v-on:show-update="showUpdate"
      v-on:show-summary="showMealSummary"
      v-on:terminate-meal="terminateMeal"
    ></meals-list>
    <div v-if="isToggled">
      <h3>Confirmed Orders</h3>
      <orders-list :orders="confirmedMealOrders"></orders-list>

      <h3>Pending Orders</h3>
      <orders-list :orders="pendingMealOrders" v-on:delete-order="deleteOrder"></orders-list>
      <div>
        <h3>Prepared Orders</h3>
        <orders-list :orders="preapredMealsOrders" v-on:mark-delivered="markDelivered"></orders-list>
        <div
          class="alert"
          :class="{'alert-success':showSuccess,'alert-danger':showFailure}"
          v-if="showSuccess || showFailure"
        >
          <strong>{{successMessage}}</strong>
          <strong>{{failMessage}}</strong>
        </div>
      </div>
    </div>
    <div v-if="isUpdateToggled">
      <h2>Add an order to a meal</h2>
    </div>
    <div v-if="isUpdateToggled">
      <label for="meals">Active meals that I'm responsible for</label>
      <select class="custom-select" v-model="selectedOptionMeal">
        <option disabled selected>-- Select an item --</option>
        <option v-for="meal in usersMeals" v-bind:key="meal.id">{{meal.id}}</option>
      </select>
    </div>
    <div v-if="isUpdateToggled">
      <label for="items">Items</label>
      <select class="custom-select" v-model="selectedOptionItem">
        <option disabled selected>-- Select an item --</option>
        <option v-for="item in allItems" v-bind:key="item.id">{{item.id}}</option>
      </select>
    </div>
    <div v-if="isUpdateToggled">
      <br>
      <button
        type="button"
        class="btn btn-outline-success"
        v-on:click.prevent="addOrderToMeal(selectedOptionMeal, selectedOptionItem, currentUser)"
      >Add order</button>
      <div
        class="alert"
        :class="{'alert-success':showSuccess,'alert-danger':showFailure}"
        v-if="showSuccess || showFailure"
      >
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
      </div>
    </div>
    <br>
    <div v-if="isMealSummaryToggled">
      <h3>Meal Summary</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Table Number</th>
            <th>Item Name</th>
            <th>Item Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in mealDetails" :key="m.id">
            <td>{{m.table_number}}</td>
            <td>{{m.name}}</td>
            <td>{{m.price}}</td>
          </tr>
        </tbody>
      </table>
      <h5>Total Price: {{currentMeal.total_price_preview}}</h5>
    </div>
  </div>
</template>
<script>
module.exports = {
  data() {
    return {
      title: "My meals",
      currentUser: 13,
      usersMeals: [],
      confirmedMealOrders: [],
      pendingMealOrders: [],
      preapredMealsOrders: [],
      allItems: [],
      selectedOptionItem: "Select an item",
      selectedOptionMeal: "Select a meal",
      isToggled: false,
      isUpdateToggled: false,
      isMealSummaryToggled: false,
      isMealSummaryVisible: false,
      successMessage: "",
      failMessage: "",
      showSuccess: false,
      showFailure: false,
      currentMeal: {},
      mealDetails: [],
      allMealOrders: []
    };
  },
  methods: {
    terminateMeal: function(meal, index) {
      axios
        .put("api/meals/" + meal.id + "/terminate")
        .then(response => {
          this.showSuccess = "Meal terminated Successfully";
          this.showSuccess = true;
        })
        .catch(error => {
          this.failMessage = "Error terminating meal";
          this.showFailure = true;
        });
      this.usersMeals.splice(index, 1);
    },
    markDelivered: function(order, index) {
      axios
        .put("/api/meals/" + order.id + "/markPreparedOrderAsDelivered")
        .then(response => {
          this.successMessage = "Success";
          this.showSuccess = true;
        })
        .catch(error => {});
    },
    deleteOrder: function(order, index) {
      axios
        .delete("/api/meal/deleteOrderOfMeal/" + order.id + "/delete")
        .then(response => {
          this.pendingMealOrders.splice(index, 1);
          this.successMessage = "Success";
          this.showSuccess = true;
        })
        .catch(error => {
          this.showFailure = true;
          this.failMessage = "Fail";
        });
    },

    showOrdersOfMeal: function(meal) {
      this.isToggled = true;

      axios
        .get("/api/meals/" + meal.id + "/confirmedOrders")
        .then(response => {
          this.confirmedMealOrders = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      axios
        .get("/api/meals/" + meal.id + "/pendingOrders")
        .then(response => {
          this.pendingMealOrders = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });

      axios
        .get("/api/meals/" + meal.id + "/preparedOrders")
        .then(response => {
          this.preapredMealsOrders = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    showUpdate: function() {
      this.isUpdateToggled = true;
      this.isToggled = false;
      this.isMealSummaryToggled = false;
    },
    showMealSummary: function(meal) {
      axios
        .get("/api/meals/" + meal.id + "/mealDetails")
        .then(response => {
          this.mealDetails = response.data.data;
        })
        .catch(error => {});

      this.actualMealDetails = [];
      this.currentMeal = meal;
      this.isMealSummaryToggled = true;
      this.isToggled = false;
      this.isUpdateToggled = false;
    },
    addOrderToMeal: function(meal_number, item_number) {
      axios
        .post("/api/meal/addOrder/" + meal_number + "/" + item_number)
        .then(response => {
            this.successMessage = "Success";
            this.showSuccess = true;
            axios.get("/api/cooks")
            .then(response => {
                let cooks = []
                response.data.forEach(cook => {
                    if (cook.shift_active) {
                        cooks.push(cook)
                    }
                })
                let msg = 'New order: meal ' + meal_number + ' item ' + item_number
                cooks.forEach(cook => {
                    console.log('Sending Message "' + msg + '" to "' + cook.name + '"');
                    this.$socket.emit('newOrderToCooks', msg, this.$store.state.user, cook);
                });
            })
        })
        .catch(error => {
          this.showFailure = true;
          this.failMessage = "Fail";
        });
    },
    getMealsOfWaiter: function() {
      //GET MEALS OF WAITER
      axios
        .get("/api/meals/waiterMeals/" + this.currentUser)
        .then(response => {
          this.usersMeals = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getAllItems: function() {
      axios
        .get("/api/items/all")
        .then(response => {
          this.allItems = response.data;
        })
        .catch(error => {});
    }
  },

  mounted() {
    this.getMealsOfWaiter();

    this.getAllItems();
  }
};
</script>
