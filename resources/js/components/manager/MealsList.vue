<template>
<div>
    <nav aria-label="Page navigation example">
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
            subTitle: 'Items of meal: ',
            meals: [],
            mealItemOrders: [],
            pagination: {},
        }
    },
    methods: {
        getMeals(url) {    
            let page_url = url || '/api/meals'
            axios.get(page_url)
                .then(response => {
                    Object.assign(this.meals, response.data.data);
                    this.makePagination(response.data.meta, response.data.links)
                })
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
        getMealOrders(id) {
            axios.get("/api/meals/" + id + "/allOrders")
            .then(response => {
                this.$emit('meal-order-details-click', response.data.data, id);
            })
        }
    },
    mounted() {
        this.getMeals()
    },
}
</script>
