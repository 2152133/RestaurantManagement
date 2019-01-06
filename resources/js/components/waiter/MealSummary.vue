<template>
    <div>
        <div class="jumbotron">
            <h3>Meal Summary</h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Table Number</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="m in getMealDetails" :key="m.id">
                    <td>{{m.table_number}}</td>
                    <td>{{m.name}}</td>
                    <td>{{m.price}}</td>
                </tr>
            </tbody>
        </table>
        <h5>Total Price: {{getCurrentMeal.total_price_preview}}</h5>
    </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                successMessage: "",
                failMessage: "",
                showSuccess: false,
                showFailure: false,
            };
        },
        methods: {
            loadMealDetails() {
                axios.get("/api/meals/" + this.getCurrentMeal.id + "/mealDetails")
                    .then(response => {
                        this.$store.commit('setMealDetails', response.data.data);
                    });
            },
        },
        computed: {
            getCurrentMeal() {
                return this.$store.getters.currentMeal;
            },
            getMealDetails() {
                return this.$store.getters.mealDetails;
            },
        },
        mounted() {
            this.loadMealDetails();
        }
    };
</script>