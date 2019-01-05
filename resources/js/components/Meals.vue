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
        <option v-for="item in allItems" v-bind:key="item.id" :value="item.id">{{item.name}}</option>
      </select>
    </div>
    <div v-if="isUpdateToggled">
      <br>
      <button
        type="button"
        class="btn btn-outline-success"
        v-on:click.prevent="createPendingMeal(selectedOptionMeal, selectedOptionItem)"
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
      currentUserId: this.$store.state.user.id,
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
      allMealOrders: [],
      notDeliveredOrdersOfMeal: [],
      currentOrder: {},
      counter: 0
    };
  },
  methods: {
    getTerminatedOrdersOfMeal: function(meal) {
      return new Promise(resolve => {
        axios
          .get("/api/meals/" + meal.id + "/notDeliveredOrders")
          .then(response => {
            this.notDeliveredOrdersOfMeal = response.data.data;
            resolve(response);
          })
          .catch(error => {});
      });
    },

    terminateMeal: function(meal, index) {
      this.getTerminatedOrdersOfMeal(meal).then(var_aux => {
        if (var_aux != 0) {
          let response = confirm(
            "There are orders not delivered, do you wish to continue?"
          );
          if (response) {
            
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
          } else {
            console.log("CANCEL");
          }
        } else {
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
        }
      });
    },

    createConfirmedOrder: function(meal_id, item_number) {
      if (!isNaN(this.currentOrder.id)) {

        axios
          .post("/api/meal/addOrder/" + meal_id + "/" + item_number)
          .then(response => {
            this.successMessage = "Success creating order!";
            this.showSuccess = true;
          })
          .catch(error => {
            this.showFailure = true;
            this.failMessage = "Fail";
          });
      } else {
        return;
      }
    },

    createPendingMeal: function(meal_number, item_number) {
      var meal_order_timeout = meal_number;
      var item_number_timeout = item_number;

      this.counter++;

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

      this.currentOrder.id = this.counter;
      this.currentOrder.state = "pending";
      this.currentOrder.item_id = item_number;
      this.currentOrder.meal_id = meal_number;
      this.currentOrder.responsible_cook_id = null;
      this.currentOrder.start = datetimeToOrder;
      this.pendingMealOrders.push(this.currentOrder);

      var self = this;

      setTimeout(function() {
        self.createConfirmedOrder(meal_order_timeout, item_number_timeout);
        self.pendingMealOrders.splice(
          self.pendingMealOrders.findIndex(o => o.id === self.counter)
        );
      }, 5000);

    },

    markDelivered: function(order, index) {
      axios
        .put("/api/meals/" + order.id + "/markPreparedOrderAsDelivered")
        .then(response => {
          this.successMessage = "Success marking delivered";
          this.showSuccess = true;
          this.preapredMealsOrders.splice(index, 1);
        })
        .catch(error => {});
    },
    deleteOrder: function(order, index) {
      this.pendingMealOrders.splice(index, 1);
      this.currentOrder = order;
      this.currentOrder = {};
      this.failMessage="Order Deleted!"
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

    getMealsOfWaiter: function() {
      //GET MEALS OF WAITER
      axios
        .get("/api/meals/waiterMeals/" + this.currentUserId)
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
