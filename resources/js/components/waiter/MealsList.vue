<template>
  <div>
    <button type="button" class="btn btn-outline-success" style="float:right">
      <router-link :to="{name: 'create_meal'}">Create Meal</router-link>
    </button>


    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>State</th>
          <th>Table Number</th>
          <th>Start</th>
          <th>End</th>
          <th>Total Price Preview</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(meal, index) in meals" :key="meal.id">
          <td>{{meal.id}}</td>
          <td>{{meal.state}}</td>
          <td>{{meal.table_number}}</td>
          <td>{{meal.start}}</td>
          <td>{{meal.end}}</td>
          <td>{{meal.total_price_preview}}</td>
          <td>{{meal.created_at}}</td>
          <td>
            <button type="button" class="btn btn-outline-primary" style="float:right" v-on:click.prevent="showOrdersOfMeal(meal)">Meal's orders' state</button>
            <button type="button" class="btn btn-outline-info" style="float:right" v-on:click.prevent="showSummary(meal)">See meal summary</button>
            <button type="button" class="btn btn-outline-danger" style="float:right" v-on:click.prevent="terminateMeal(meal, index)">Terminate meal</button>
            <button type="button" class="btn btn-outline-warning" style="float:right" v-on:click.prevent="showUpdate(meal, index)">Add order to meal</button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</template>
<script>
  module.exports = {
    props: ["meals", "meta", "links", "user"],
    data: function () {
      return {};
    },
    methods: {
      showOrdersOfMeal: function (meal) {
        this.$router.push({ name: 'mealOrdersState', params: { meal } });
      },
      showUpdate: function () {
        this.$router.push({ name: 'addOrderToMeal' });
      },
      showSummary: function (meal) {
        this.$store.commit('setCurrentMeal', meal);
        this.$router.push({ name: 'mealSummary' });
      },
      terminateMeal: function (meal, index) {
        this.getTerminatedOrdersOfMeal(meal).then(var_aux => {
          if (var_aux != 0) {
            let response = confirm(
              "There are orders not delivered, do you wish to continue?"
            );
            if (response) {
              axios.put("api/meals/" + meal.id + "/terminate")
                .then(response => {
                  this.showSuccess = "Meal terminated Successfully";
                  this.showSuccess = true;
                })
                .catch(error => {
                  this.failMessage = "Error terminating meal";
                  this.showFailure = true;
                });
              this.$store.commit('removeMealFromUserMeals', index);
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
            this.$store.commit('removeMealFromUserMeals', index);
          }
        });
      },
      getTerminatedOrdersOfMeal: function (meal) {
        return new Promise(resolve => {
          axios
            .get("/api/meals/" + meal.id + "/notDeliveredOrders")
            .then(response => {
              this.$store.commit('setNotDeliveredOrdersOfMeal', response.data.data);
              resolve(response);
            })
            .catch(error => { });
        });
      },
    }
  };
</script>