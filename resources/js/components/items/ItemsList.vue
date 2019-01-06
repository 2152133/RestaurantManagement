<template>
<div>
    <h3>Items</h3>
    <pagination :objects="items" :meta="meta" :links="links" @refreshObjects="refreshItems"></pagination>
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
    <br/>
    <br/>
    <br/>
</div>
</template>

<script>
export default {
    props: ['items', 'meta', 'links'],
    data() {
        return {
            editingItem: null,
            pagination: {},
        }
    },
    methods: {
        editItem(item){
            this.editingItem = item;
            this.$emit('edit-click', item)
        },		
        deleteItem(item){
            this.editingItem = null;
            this.$emit('delete-click', item)
        },
        itemImageURL(photo) {
            return "storage/items/" + String(photo)
        },
        compactDescription(text) {
            return text.length > 100 ? text.substr( 0, 70 )+'...' : text
        },
        refreshItems(items, meta, links) {
            this.$emit('refreshItems', items, meta, links)
        }
    },
    computed: {
        isManager() {
            return this.$store.getters.isManager
        },
    },
}
</script>
