<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>
    <meals-list :meals="usersMeals" :meta="usersMealsMeta" :links="usersMealsLinks"></meals-list>
    <div>
      <h3>Confirmed Orders</h3>
      <orders-list
        :orders="confirmedMealOrders"
        :meta="confirmedOrdersMeta"
        :links="confirmedOrdersLinks"
      ></orders-list>
      <h3>Pending Orders</h3>
      <orders-list
        :orders="pendingMealOrders"
        :meta="pendingOrdersMeta"
        :links="pendingOrdersLinks"
      ></orders-list>
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
      pendingOrdersLinks: []
    };
  },
  methods: {},

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
  }
};
</script>
