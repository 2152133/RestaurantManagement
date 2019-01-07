<template>
<div>
    <nav aria-label="Page navigation example">
        <div>
            <a class="btn btn-info" @click.prevent="reset()">Reset</a>
            <a class="btn btn-info" @click.prevent="filterByState('active')">Active</a>
            <a class="btn btn-info" @click.prevent="filterByState('terminated')">Terminated</a>
            <a class="btn btn-info" @click.prevent="filterByState('paid')">Paid</a>
            <a class="btn btn-info" @click.prevent="filterByState('not paid')">Not Paid</a>
            <a class="btn btn-info" @click.prevent="showSelectWaiter()">Waiter</a>
            <a class="btn btn-info" @click.prevent="showSelectDate()">Date</a>
        </div>
        <hr>
        <div> 
            <div v-if="toggleWaiter">
                <label>Waiter ID</label>
                <select class="form-control" v-model="data.id">
                    <option disabled selected>-- Select an ID --</option>
                    <option v-for="id in waitersIds" :key="id">{{id}}</option>
                </select>
            </div>
            <div v-if="toggleDate">
                <label for="Date">Date (yyyy-mm-dd)</label>
                <input class="form-control" type="text" v-model="data.dates">
            </div>
        </div>
        <br v-if="toggleWaiter || toggleDate">
        <div>
            <button v-if="toggleWaiter" type="button" class="btn btn-success" @click.prevent="filterByWaiter(data.id)">Confirm</button>
            <button v-if="toggleDate" type="button" class="btn btn-success" @click.prevent="filterByDate(data.dates)">Confirm</button>
        </div>
        <hr v-if="toggleWaiter || toggleDate">
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getMeals(pagination.prev_page_url)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getMeals(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>State</th>
                <th>Table</th>
                <th>Date</th>
                <th>Waiter</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody v-for="meal in meals" v-bind:key="meal.id">
            <td>{{ meal.id }}</td>
            <td>{{ meal.state }}</td>
            <td>{{ meal.table_number }}</td>
            <td>{{ meal.created_at.date }}</td>
            <td>{{ meal.responsible_waiter ? meal.responsible_waiter.name : " - NONE - " }}</td>
            <td>{{ meal.total_price_preview }}</td>
            <td>
                <a class="btn btn-sm btn-warning" v-on:click.prevent="getMealOrders(meal.id)">Details</a> 
                <a v-if="meal.state == 'terminated'" class="btn btn-sm btn-warning" v-on:click.prevent="declareMealAsNotPaid(meal)">Not Paid</a>
            </td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    data() {
        return {
            title: 'Meals',
            meals: [],
            pagination: {},
            filter: [],
            filteredSearch: false,
            waitersIds: [],
            data: {
                id: null,
                dates: ""
            },
            toggleWaiter: false,
            toggleDate: false
        }
    },
    methods: {
        getMeals(url) {
            if (!this.filteredSearch) {
                let page_url = url || '/api/meals/activeAndTerminated'
                axios.get(page_url)
                .then(response => {
                    Object.assign(this.meals, response.data.data);
                    this.makePagination(response.data.meta, response.data.links)
                })
            }else {
                axios.get(url)
                .then(response => {
                    Object.assign(this.meals, response.data.data)
                    this.makeFilteredPagination(response.data)
                })
            }
        },
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
            }
            this.pagination = pagination
        },
        makeFilteredPagination(data) {
            let pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url,
            }
            this.pagination = pagination
        },
        getMealOrders(id) {
            axios.get("/api/meals/" + id + "/allOrders")
            .then(response => {
                this.$emit('meal-order-details-click', response.data.data, id);
            })
        },
        filterByState(state) {
            axios.get('/api/meals/filtered?state=' + state)
            .then(response => {
                this.filteredSearch = true
                this.meals = response.data.data
                this.makeFilteredPagination(response.data)
            })
        },
        reset() {
            this.filteredSearch = false
            this.getMeals()
        },
        declareMealAsNotPaid(meal){
            axios.patch('/api/meals/' + meal.id + '/declareNotPaid')
            .then(response => {
                this.filterByState('terminated');

            })
        },
        filterByWaiter(id) {
            axios.get('/api/meals/filtered?responsible_waiter_id=' + id)
            .then(response => {
                this.filteredSearch = true
                this.meals = response.data.data
                this.makeFilteredPagination(response.data)
            })
        },
        filterByDate(date) {
            axios.get('/api/meals/filtered?start=' + date)
            .then(response => {
                this.filteredSearch = true
                this.meals = response.data.data
                this.makeFilteredPagination(response.data)
            })
        },
        getWaitersIds() {
            axios.get('/api/waiters') 
            .then(response => {
                response.data.forEach(waiter => {
                    this.waitersIds.push(waiter.id)
                });
            })
        },
        showSelectWaiter() {
            this.toggleWaiter = this.toggleWaiter ? false : true
            this.toggleDate = false
        },
        showSelectDate() {
            this.toggleDate = this.toggleDate ? false : true
            this.toggleWaiter = false
        }

    },
    mounted() {
        this.getMeals()
        this.getWaitersIds()
    },
}
</script>
