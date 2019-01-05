<template>
  <div>
      <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <div>
            <div v-if="toggleWorkerTotalBool">
                <a @click.prevent="toggleWorkerTotal()" class="btn btn-info">Workers</a>
                <a v-if="toggleMealsOrdersBool" @click.prevent="toggleMealsOrders()" class="btn btn-info">Meals</a>
                <a v-if="toggleMealsOrdersBool && toggleTotalAvgHandledBool" @click.prevent="toggleTotalAvgHandled()" class="btn btn-info">Average Handled</a>
                <a v-if="toggleMealsOrdersBool && !toggleTotalAvgHandledBool" @click.prevent="toggleTotalAvgHandled()" class="btn btn-info">Total</a>
                <a v-if="!toggleMealsOrdersBool" @click.prevent="toggleMealsOrders()" class="btn btn-info">Orders</a>
                <a v-if="!toggleMealsOrdersBool && toggleTotalAvgHandledBool" @click.prevent="toggleTotalAvgHandled()" class="btn btn-info">Average Handled</a>
                <a v-if="!toggleMealsOrdersBool && !toggleTotalAvgHandledBool" @click.prevent="toggleTotalAvgHandled()" class="btn btn-info">Total</a>
            </div>
            <div v-else>
                <a v-if="!toggleWorkerTotalBool" @click.prevent="toggleWorkerTotal()" class="btn btn-info">Meals/Orders</a>
                <a v-if="toggleWorkerBool" @click.prevent="toggleWorker()" class="btn btn-info">Waiter</a>
                <a v-if="!toggleWorkerBool" @click.prevent="toggleWorker()" class="btn btn-info">Cook</a>
                <a v-if="!toggleWorkerBool && !toggleOrderMealBool" @click.prevent="toggleOrderMeal()" class="btn btn-info">Order</a>
                <a v-if="!toggleWorkerBool && toggleOrderMealBool" @click.prevent="toggleOrderMeal()" class="btn btn-info">Meal</a>
            </div>
        </div>
        <hr>
        <div v-if="toggleWorkerTotalBool">

        </div>
        <div v-else>
            <div v-if="toggleWorkerBool">
                <label>Cook ID (Orders)</label>
                <select class="form-control" v-model="data.id">
                    <option disabled selected>-- Select an ID --</option>
                    <option v-for="id in cooksIds" :key="id">{{id}}</option>
                </select>
            </div>
            <div v-else>
                
                <label v-if="toggleOrderMealBool">Waiter ID (Orders)</label>
                <label v-else>Waiter ID (Meals)</label>
                    <select class="form-control" v-model="data.id">
                        <option disabled selected>-- Select an ID --</option>
                        <option v-for="id in waitersIds" :key="id">{{id}}</option>
                    </select>
            </div>
        </div>
        <label for="Date">Date (yyyy-mm-dd,yyyy-mm-dd)</label>
        <input class="form-control" type="text" v-model="data.dates">
        <br>
        <div v-if="toggleWorkerTotalBool">
            <button v-if="toggleMealsOrdersBool && toggleTotalAvgHandledBool" type="button" class="btn btn-success" @click.prevent="getTotalOrdersFromGivenMonth(data.dates)">Confirm</button>
            <button v-if="toggleMealsOrdersBool && !toggleTotalAvgHandledBool" type="button" class="btn btn-success" @click.prevent="getAVGTimeToHandleEachMealOnGivenMonth(data.dates)">Confirm</button>
            <button v-if="!toggleMealsOrdersBool" type="button" class="btn btn-success" @click.prevent="getTotalMealsFromGivenMonth(data.dates)">Confirm</button>
        </div>
        <div v-else>
            <button v-if="toggleWorkerBool" type="button" class="btn btn-success" @click.prevent="getAVGNumberOfOrdersHandledOnGivenDatesForEachCook(data.id, data.dates)">Confirm</button>
            <button v-if="!toggleWorkerBool && toggleOrderMealBool" type="button" class="btn btn-success" @click.prevent="getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter(data.id, data.dates)">Confirm</button>
            <button v-if="!toggleWorkerBool && !toggleOrderMealBool" type="button" class="btn btn-success" @click.prevent="getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter(data.id, data.dates)">Confirm</button>
        </div>
        <hr>
        <ve-line :data="chartData"></ve-line>
  </div>
</template>
 
<script>
import VeLine from 'v-charts/lib/line.common'
export default {
    components: { VeLine },
    data () {
        return {
            title: 'Statistics',
            toggleWorkerBool: true,
            toggleOrderMealBool: true,
            toggleWorkerTotalBool: true,
            toggleMealsOrdersBool: true,
            toggleTotalAvgHandledBool: true,
            cooksIds: [],
            waitersIds: [],
            data: {
                id: null,
                dates: ""
            },
            chartData: {
                columns: [],
                rows: []
            }
        }
    },
     methods: {
        getAVGNumberOfOrdersHandledOnGivenDatesForEachCook(id, dates) {            
            axios.get('/api/ordersHandledCook/' + id + '/dates/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'AVG Orders']
                this.chartData.rows = response.data
            })
        },
        getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter(id, dates) {            
            axios.get('/api/ordersHandledWaiter/' + id + '/dates/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'AVG Orders']
                this.chartData.rows = response.data
            })
        },
        getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter(id, dates) {
            axios.get('/api/mealsHandledWaiter/' + id + '/dates/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'AVG Meals']
                this.chartData.rows = response.data
            })
        },
        getTotalOrdersFromGivenMonth(dates) {
            axios.get('/api/totalOrders/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'Total Orders']
                this.chartData.rows = response.data
            })
        },
        getTotalMealsFromGivenMonth(dates) {
            axios.get('/api/totalMeals/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'Total Meals']
                this.chartData.rows = response.data
            })
        },
        getAVGTimeToHandleEachMealOnGivenMonth(dates) {
            axios.get('/api/timeToHandleMeal/' + dates)
            .then(response => {
                this.chartData.columns = ['date', 'AVG time to handle Meal']
                this.chartData.rows = response.data
            })
        },
        getCooksIds() {
            axios.get('/api/cooks') 
            .then(response => {
                response.data.forEach(cook => {
                    this.cooksIds.push(cook.id)
                });
            })
        },
        getWaitersIds() {
            axios.get('/api/waiters') 
            .then(response => {
                response.data.forEach(cook => {
                    this.waitersIds.push(cook.id)
                });
            })
        },
        toggleWorker() {
            this.toggleWorkerBool = this.toggleWorkerBool ? false : true
        },
        toggleOrderMeal() {
            this.toggleOrderMealBool = this.toggleOrderMealBool ? false : true
        },
        toggleWorkerTotal() {
            this.toggleWorkerTotalBool = this.toggleWorkerTotalBool ? false : true
        },
        toggleMealsOrders() {
            this.toggleMealsOrdersBool = this.toggleMealsOrdersBool ? false : true
        },
        toggleTotalAvgHandled() {
            this.toggleTotalAvgHandledBool = this.toggleTotalAvgHandledBool ? false : true
        }
    },
    mounted() {
        // cook     -> 66 2017-10-25,2017-10-26,2017-10-27,2017-10-28,2017-10-29,2017-10-30
        // waiter   -> 13 2017-10-25,2017-10-26,2017-10-27,2017-10-28,2017-10-29,2017-10-30
        this.getCooksIds()
        this.getWaitersIds()
    }
}
</script> 