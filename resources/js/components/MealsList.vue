<template>
  <div>
    <button type="button" class="btn btn-outline-success" style="float:right">
      <router-link :to="{name: 'create_meal'}">Create Meal</router-link>
    </button>
    
    <button
      type="button"
      class="btn btn-outline-warning"
      style="float:right"
      v-on:click.prevent="showUpdate(meal, index)"
    >Add order to meal</button>
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
            <button
              type="button"
              class="btn btn-outline-primary"
              style="float:right"
              v-on:click.prevent="showOrdersOfMeal(meal)"
            >Meal's orders' state</button>
            <button
              type="button"
              class="btn btn-outline-info"
              style="float:right"
              v-on:click.prevent="showSummary(meal)"
            >See meal summary</button>
            <button
              type="button"
              class="btn btn-outline-danger"
              style="float:right"
              v-on:click.prevent="terminateMeal(meal, index)"
            >Terminate meal</button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</template>
<script>
module.exports = {
  props: ["meals", "meta", "links", "user"],
  data: function() {
    return {};
  },
  methods: {
    showOrdersOfMeal: function(meal) {
      this.$emit("show-ordersOfMeal", meal);
    },
    showUpdate: function() {
      this.$emit("show-update");
    },
    showSummary: function(meal) {
      this.$emit("show-summary", meal);
    },
    terminateMeal:function(meal, index){
      this.$emit("terminate-meal", meal, index);
    }
  }
};
</script>
