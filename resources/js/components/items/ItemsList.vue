<template>
<div>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getItems(pagination.prev_page_url)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getItems(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th v-if="isManager">Actions</th>
            </tr>
        </thead>
        <tbody v-for="item in items" v-bind:key="item.id">
            <td><img v-bind:src="itemImageURL(item.photo_url)" height="80" width="100"></td>
            <td>{{ item.name }}</td>
            <td>{{ compactDescription(item.description) }}</td>
            <td>{{ item.price }} €</td>
            <td v-if="isManager"> 
                <a @click.prevent="editItem(item)" class="btn btn-sm btn-warning">Edit</a>
                <a @click.prevent="deleteItem(item)" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    props: ['items'],
    data() {
        return {
            editingItem: null,
            pagination: {},
        }
    },
    methods: {
        editItem(item){
            this.editingItem = item;
            this.$emit('edit-click', item);
        },		
        deleteItem(item){
            this.editingItem = null;
            this.$emit('delete-click', item);
        },
        itemImageURL(photo) {
            return "storage/items/" + String(photo);
        },
        compactDescription(text) {
            return text.length > 100 ? text.substr( 0, 70 )+'...' : text;
        },
         getItems(url) {    
            let page_url = url || '/api/items'
            axios.get(page_url)
                .then(response => {
                    Object.assign(this.items, response.data.data);
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
    },
    computed: {
        isManager() {
            return this.$store.getters.isManager
        },
    },
    mounted() {
        this.getItems()
    },
}
</script>
