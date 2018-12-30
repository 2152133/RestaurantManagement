<template>
  <div>
    <pagination :objects="orders" :meta="meta" :links="links" @refreshObjects="refreshOrders"></pagination>
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>State</th>
          <th>Item Name</th>
          <th>Meal Id</th>
          <th>Responsable cook</th>
          <th>Start</th>
          <th>End</th>
          <th>created_at</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(order,index) in orders" :key="order.id">
          <td>{{order.id}}</td>
          <td>{{order.state}}</td>
          <td>{{order.item_id}}</td>
          <td>{{order.meal_id}}</td>
          <td>{{order.responsible_cook_id}}</td>
          <td>{{order.start}}</td>
          <td>{{order.end}}</td>
          <td>{{order.created_at.date}}</td>
          <td>
            <div v-if="order.state == 'confirmed'">
              <button
                type="Submit"
                class="btn btn-primary btn-sm btn-block"
                @click="assignOrderToCook(order, index)">
                  Assign to me
              </button>
            </div>
            <button
              type="Submit"
              class="btn btn-danger btn-sm btn-block"
              @click="declareOrderAsPrepared(order, index)">
                Prepared
            </button>
            <div v-if="order.state == 'pending'">
              <button
                type="button"
                class="btn btn-outline-danger"
                style="float:right"
                @click="deleteOrder(order, index)">
                  Delete Order
              </button>
            </div>
            <div v-if="order.state == 'prepared'">
              <button
                type="button"
                class="btn btn-outline-success"
                style="float:right"
                @click="markDelivered(order, index)">
                  Mark as Delivered
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
module.exports = {
  props: ["orders", "meta", "links"],
  data: function() {
    return {};
  },
  methods: {
    assignOrderToCook(order, index) {
      this.$emit("assign-to-cook", order, index);
    },
    declareOrderAsPrepared(order, index) {
      this.$emit("declare-order-as-prepared", order, index);
    },
    refreshOrders(orders, meta, links) {
      this.$emit("refreshOrders", orders, meta, links);
    },
    deleteOrder(order, index) {
      this.$emit("delete-order", order, index);
    },
    markDelivered(order, index) {
      this.$emit("mark-delivered", order, index);
    }
  }
};
</script>