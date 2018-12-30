<template>
<div>
    <nav aria-label="Page navigation example">
        <div>
            <a class="btn btn-primary" @click.prevent="reset()">Pending</a>
            <a class="btn btn-primary" @click.prevent="filterByState('paid')">Paid</a>
            <a class="btn btn-primary" @click.prevent="filterByState('not paid')">Not Paid</a>
        </div>
        <br>
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getInvoices(pagination.prev_page_url)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getInvoices(pagination.next_page_url)">Next</a></li>
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
        <tbody v-for="invoice in invoices" v-bind:key="invoice.id">
            <td>{{ invoice.state }}</td>
            <td>{{ invoice.table_number }}</td>
            <td>{{ invoice.created_at.date }}</td>
            <td>{{ invoice.responsible_waiter ? invoice.responsible_waiter.name : " - NONE - " }}</td>
            <td>{{ invoice.total_price }}</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="getInvoiceDetails(invoice)">Details</a> 
            </td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    data() {
        return {
            invoices: [],
            pagination: {},
            filter: [],
            filteredSearch: false
        }
    },
    methods: {
        getInvoices(url) {
            if (!this.filteredSearch) {
                let page_url = url || '/api/invoices/pending'
                axios.get(page_url)
                .then(response => {
                    Object.assign(this.invoices, response.data.data);
                    this.makePagination(response.data.meta, response.data.links)
                })
            }else {
                axios.get(url)
                .then(response => {
                    Object.assign(this.invoices, response.data.data)
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
        filterByState(state) {
            axios.get('/api/invoices/filtered?state=' + state)
            .then(response => {
                this.filteredSearch = true
                this.invoices = response.data.data
                this.makeFilteredPagination(response.data)
            })
        },
        reset() {
            this.filteredSearch = false
            this.getInvoices()
        },
        getInvoiceDetails(invoice) {
            console.log(invoice)
            this.$emit('invoice-details-click', invoice);
        },
    },
    mounted() {
        this.getInvoices()
    },
}
</script>
