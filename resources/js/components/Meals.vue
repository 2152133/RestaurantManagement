<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>
    <meals-list
      :meals="usersMeals"
      :meta="usersMealsMeta"
      :links="usersMealsLinks"
      v-on:show-div="showDiv"
      v-on:show-update="showUpdate"
    ></meals-list>
    <div v-if="isToggled">
      <h3>Confirmed Orders</h3>
      <orders-list
        :orders="confirmedMealOrders"
        :meta="confirmedOrdersMeta"
        :links="confirmedOrdersLinks"
      ></orders-list>
    </div>
    <div v-if="isToggled">
      <h3>Pending Orders</h3>
      <orders-list
        :orders="pendingMealOrders"
        :meta="pendingOrdersMeta"
        :links="pendingOrdersLinks"
      ></orders-list>
    </div>
    <div v-if="isUpdateToggled">
      <h2>Add an order to a meal</h2>

      <label for="meals">Active meals that I'm responsible for</label>
      <select class="custom-select" v-model="selectedOptionMeal">
        <option disabled selected>-- Select an item --</option>
        <option v-for="meal in usersMeals" v-bind:key="meal.id">{{meal.id}}</option>
      </select>
      
      <label for="items">Items</label>
      <select class="custom-select" v-model="selectedOptionItem">
        <option disabled selected>-- Select an item --</option>
        <option v-for="item in allItems" v-bind:key="item.id">{{item.id}}</option>
      </select>
      <br>
      <button type="button" class="btn btn-outline-success"
      v-on:click.prevent="addOrderToMeal(selectedOptionMeal, selectedOptionItem, currentUser)">Add order</button>
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
      usersMealsMeta: [],
      usersMealsLinks: [],
      confirmedMealOrders: [],
      confirmedOrdersMeta: [],
      confirmedOrdersLinks: [],
      pendingMealOrders: [],
      pendingOrdersMeta: [],
      pendingOrdersLinks: [],
      isToggled: false,
      allItems: [],
      selectedOptionItem: "Select an item",
      selectedOptionMeal: "Select a meal",
      isUpdateToggled: false
    };
  },
  methods: {
    showDiv: function() {
      this.isToggled = true;
    },
    showUpdate: function() {
      this.isUpdateToggled = true;
    },
    addOrderToMeal:function(meal_number, item_number){
      axios.post("/api/meal/addOrder/"+ meal_number+"/"+item_number)
      .then(response=>{

      })
      .catch(error=>{

      });
    }
  },

  mounted() {
    axios
      .get("/api/meals/waiterMeals/" + this.currentUser)
      .then(response => {
        this.usersMeals = response.data.data;
        this.usersMealsMeta = response.data.meta;
        this.usersMealsLinks = response.data.links;

        //CONFIRMED

        this.usersMeals.forEach(index => {
          axios
            .get("/api/meals/" + index.id + "/confirmedOrders")
            .then(response => {
              this.confirmedMealOrders = response.data.data;
              this.confirmedOrdersMeta = response.data.meta;
              this.confirmedOrdersLinks = response.data.links;
            })
            .catch(function(error) {
              console.log(error);
            });
        });

        //PENDING
        this.usersMeals.forEach(index => {
          axios
            .get("/api/meals/" + index.id + "/pendingOrders")
            .then(response => {
              this.pendingMealOrders = response.data.data;
              this.pendingOrdersMeta = response.data.meta;
              this.pendingOrdersLinks = response.data.links;
            })
            .catch(function(error) {
              console.log(error);
            });
        });
      })
      .catch(function(error) {
        console.log(error);
      });

    axios
      .get("/api/items/all")
      .then(response => {
        this.allItems = response.data;
        console.log(this.allItems);
      })
      .catch(error => {});
  }
};
</script>
