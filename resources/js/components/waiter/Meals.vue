<template>
  <div>
    <div class="jumbotron">
      <h1>My meals</h1>
    </div>

    <meals-list :meals="getUserMeals"></meals-list>

  </div>
</template>
<script>
module.exports = {
  data() {
    return {
      
    };
  },
  methods: {
    getMealsOfWaiter: function() {
      axios.get("/api/meals/waiterMeals/" + this.getCurrentUser.id)
        .then(response => {
          this.$store.commit('setUserMeals', response.data.data);
        })
    },
    getItems: function() {
      axios.get("/api/items/all")
        .then(response => {
          this.$store.commit('setAllItems', response.data);
        })
    }
  },
  computed: {
    getCurrentUser(){
      return this.$store.getters.getAuthUser;
    },
    getUserMeals(){
      return this.$store.getters.userMeals;
    },  
  },
  mounted() {
    this.getMealsOfWaiter();
    this.getItems();
  }
};
</script>
