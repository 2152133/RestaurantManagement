<template>
  <div>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1/1/2000</td>
          <td>Meal</td>
          <td>Refeição para a mesa 5</td>
          <td>...</td>
        </tr>
      </tbody>
    </table>
    <button class="btn btn-danger">
      <router-link :to="{name: 'dashboard'}">Back to Dashboard</router-link>
    </button>
  </div>
</template>
<script>
module.exports = {
  data: function() {
    return {};
  },
  methods: {},
  mounted() {}
};
</script>
<style>
a {
  color: #FFF;
}
</style>


