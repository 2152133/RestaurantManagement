<template>
  <div>
    <button type="button" class="btn btn-outline-success" style="float:right">
      <router-link :to="{name: 'create_meal'}">Create Meal</router-link>
    </button>
    
    <button
      type="button"
      class="btn btn-outline-warning"
      style="float:right"
      v-on:click.prevent="showUpdate()"
    >Add order to meal</button>
    <button
      type="button"
      class="btn btn-outline-primary"
      style="float.right"
      v-on:click.prevent="showDiv()"
    >See meal's details</button>
    <pagination :objects="meals" :meta="meta" :links="links"></pagination>
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
        <tr v-for="meal in meals" :key="meal.id">
          <td>{{meal.id}}</td>
          <td>{{meal.state}}</td>
          <td>{{meal.table_number}}</td>
          <td>{{meal.start}}</td>
          <td>{{meal.end}}</td>
          <td>{{meal.total_price_preview}}</td>
          <td>{{meal.created_at}}</td>
          <td></td>
          <td></td>
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
    showDiv: function() {
      this.$emit("show-div");
    },
    showUpdate: function() {
      this.$emit("show-update");
    }
  }
};
</script>
