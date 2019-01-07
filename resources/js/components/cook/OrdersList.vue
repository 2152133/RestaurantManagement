<template>
  <div>
    <pagination :objects="orders" :meta="meta" :links="links" @refreshObjects="refreshOrders"></pagination>
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>State</th>
          <th>Date</th>
          <th>Item Name</th>
          <th>Responsable cook</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(order,index) in orders" :key="order.id">
          <td>{{order.id}}</td>
          <td>{{order.state}}</td>
          <td>{{order.start}}</td>
          <td v-if="order.item != null">{{order.item.name}}</td>
          <td v-else></td>
          <td v-if="order.responsible_cook != null">{{order.responsible_cook.name}}</td>
          <td v-else></td>
          <td>
            <div v-if="order.state == 'confirmed' && getAuthUser.type == 'cook'">
              <button type="Submit" class="btn btn-primary btn-sm btn-block" @click="assignOrderToCook(order, index)">
                Assign to me
              </button>
            </div>
            <button v-if="getAuthUser.type == 'cook'" type="Submit" class="btn btn-danger btn-sm btn-block" @click="declareOrderAsPrepared(order, index)">
              Prepared
            </button>
            <div v-if="order.state == 'pending'">
              <button v-if="getAuthUser.type == 'waiter'" type="button" class="btn btn-outline-danger" style="float:right" @click="deleteOrder(order, index)">
                Delete Order
              </button>
            </div>
            <div v-if="order.state == 'prepared' && getAuthUser.type == 'waiter'">
              <button type="button" class="btn btn-outline-success" style="float:right" @click="markDelivered(order, index)">
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
    data: function () {
      return {};
    },
    methods: {
      assignOrderToCook(order, index) {
        this.$store.commit('setCurrentOrder', order);
        axios.patch('/api/orders/' + order.id + '/assignTo/' + this.$store.getters.getAuthUser.id)
          .then((response) => {
            // handle success
            this.sendOrderAssignment()
          });
      },
      sendOrderAssignment() {
        this.$socket.emit('order_assignment_update');
      },
      declareOrderAsPrepared(order, index) {
        this.$store.commit('setCurrentOrder', order);
        axios.patch('/api/orders/' + order.id + '/preparedBy/' + this.$store.getters.getAuthUser.id)
          .then((response) => {
            // handle success
            let msg = 'Order ' + order.id + ' is ready!'
            this.$socket.emit('message_responsable_waiter', msg, this.$store.getters.getAuthUser, order.meal.responsible_waiter_id)
            this.sendOrderPrepared();
          });
      },
      sendOrderPrepared() {
        this.$socket.emit('order_prepared', this.currentOrder);
        this.$store.commit('setCurrentOrder', {});
      },
      refreshOrders(orders, meta, links) {
        this.$emit("refreshOrders", orders, meta, links);
      },
      deleteOrder(order, index) {
        this.$emit("delete-order", order, index);
      },
      markDelivered(order, index) {
        this.$emit("mark-delivered", order, index);
      },
    },
    computed: {
      getAuthUser() {
        return this.$store.getters.getAuthUser;
      }
    }
  };
</script>