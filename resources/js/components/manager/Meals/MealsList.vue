<template>
<div>
    <nav aria-label="Page navigation example">
        <div>
            <a class="btn btn-primary" @click.prevent="reset()">Reset</a>
            <a class="btn btn-primary" @click.prevent="filterByState('active')">Active</a>
            <a class="btn btn-primary" @click.prevent="filterByState('terminated')">Terminated</a>
            <a class="btn btn-primary" @click.prevent="filterByState('paid')">Paid</a>
            <a class="btn btn-primary" @click.prevent="filterByState('not paid')">Not Paid</a>
        </div>
        <br>
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
                <th>State</th>
                <th>Table</th>
                <th>Date</th>
                <th>Waiter</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody v-for="meal in meals" v-bind:key="meal.id">
            <td>{{ meal.state }}</td>
            <td>{{ meal.table_number }}</td>
            <td>{{ meal.created_at.date }}</td>
            <td>{{ meal.responsible_waiter ? meal.responsible_waiter.name : " - NONE - " }}</td>
            <td>{{ meal.total_price_preview }}</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="getMealOrders(meal.id)">Details</a> 
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
            filteredSearch: false
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
        }

    },
    mounted() {
        this.getMeals()
    },
}
</script>
