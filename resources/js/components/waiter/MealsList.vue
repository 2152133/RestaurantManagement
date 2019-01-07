<template>
  <div>
    <button type="button" class="btn btn-success" style="float:right" @click="showCreateMeal">
      Create Meal
    </button>

    <pagination :objects="meals" :meta="meta" :links="links" @refreshObjects="refreshMeals"></pagination>
    <button type="button" class="btn btn-warning" style="float:right" v-on:click.prevent="showUpdate">Add order to meal</button>
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>State</th>
          <th>Table Number</th>
          <th>Start</th>
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
          <td>{{meal.total_price_preview}}</td>
          <td>{{meal.created_at.date}}</td>
          <td>
            <button type="button" class="btn btn-primary btn-block" style="float:right" v-on:click.prevent="showOrdersOfMeal(meal)">Meal's orders' state</button>
            <button type="button" class="btn btn-info btn-block" style="float:right" v-on:click.prevent="showSummary(meal)">See meal summary</button>
            <button type="button" class="btn btn-danger btn-block" style="float:right" v-on:click.prevent="terminateMeal(meal, index)">Terminate meal</button>
            
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</template>
<script>
  module.exports = {
    props: ["meals", "meta", "links"],
    data: function () {
      return {};
    },
    methods: {
      showOrdersOfMeal: function (meal) {
        this.$store.commit('setCurrentMeal', meal);
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
        axios.get("/api/meals/" + meal.id + "/notDeliveredOrders")
          .then(response => {
            console.log(response);
            this.$store.commit('setNotDeliveredOrdersOfMeal', response.data);
            if (this.getNotDeliveredOrdersOfMeal.length > 0) {
              let userAnswer = confirm("There are orders not delivered, do you wish to continue?");
              if (userAnswer) {
                this.terminateMealOnDB(meal, index);
              } else {
                console.log("CANCEL");
              }
            } else {
              this.terminateMealOnDB(meal, index);
            }
          });
      },
      terminateMealOnDB(meal, index) {
        axios.put("api/meals/" + meal.id + "/terminate")
          .then(response => {
            this.showSuccess = "Meal terminated Successfully";
            this.showSuccess = true;
            this.$store.commit('removeMealFromUserMeals', index);
            this.sendTerminateMeal();
          })
          .catch(error => {
            this.failMessage = "Error terminating meal";
            this.showFailure = true;
          });
      },
      showCreateMeal() {
        this.$router.push({ name: 'create_meal' });
      },
      refreshMeals(meals, meta, links) {
        this.$emit('refreshMeals', meals, meta, links);
      },
      sendTerminateMeal(){
        this.$socket.emit('meal_terminated');
      },
    },
    computed: {
      getNotDeliveredOrdersOfMeal() {
        return this.$store.getters.notDeliveredOrdersOfMeal;
      }
    }
  };
</script>