<template>
  <div>
    <div class="jumbotron">
      <h1>My meals</h1>
    </div>

    <meals-list :meals="getUserMeals" :meta="getUserMealsMeta" :links="getUserMealsLinks" @refreshMeals="refreshUserMeals"></meals-list>

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
          console.log(response);
          this.$store.commit('setUserMeals', response.data.data);
          this.$store.commit('setUserMealsMeta', response.data.meta);
          this.$store.commit('setUserMealsLinks', response.data.links);

          console.log(this.getUserMealsMeta);
        })
    },
    getItems: function() {
      axios.get("/api/items/all")
        .then(response => {
          this.$store.commit('setAllItems', response.data);
        })
    },
    refreshUserMeals(userMeals, userMealsMeta, userMealsLinks){
      this.$store.commit('setUserMeals', userMeals);
      this.$store.commit('setUserMealsMeta', userMealsMeta);
      this.$store.commit('setUserMealsLinks', userMealsLinks);
    },
  },
  computed: {
    getCurrentUser(){
      return this.$store.getters.getAuthUser;
    },
    getUserMeals(){
      return this.$store.getters.userMeals;
    },  
    getUserMealsMeta(){
      return this.$store.getters.userMealsMeta;
    },  
    getUserMealsLinks(){
      return this.$store.getters.userMealsLinks;
    },  
  },
  mounted() {
    this.getMealsOfWaiter();
    this.getItems();
  }
};
</script>
