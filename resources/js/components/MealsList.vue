<template>
  <div>
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
          <td>
            <button type="button" class="btn btn-outline-primary">See meal's details</button>
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
  methods: {}
};
</script>
