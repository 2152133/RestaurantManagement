<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}} <button v-if="isManager" class="btn btn-sm btn-dark"><router-link to="newItem">Add new Item</router-link></button></h1>
        </div>
        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
        <items-list :items="getItems" :meta="getItemsMeta" :links="getItemsLinks" @refreshItems="refreshItems" @edit-click="editItem" @delete-click="deleteItem"></items-list>
        <item-edit :item="currentItem" @item-saved="savedItem" @item-canceled="cancelEdit" v-if="currentItem"></item-edit>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: 'Items',
            showSuccess: false,
            successMessage: '',
            currentItem: null,
            items: [],
        }
    },
    methods: {
        editItem(item){
            this.currentItem = item
            this.showSuccess = false
        },
        deleteItem(item){
            axios.delete('api/item/' + item.id)
            .then(response => {
                this.loadItems()
            })
        },
        savedItem(){
            this.currentItem = null
            this.showSuccess = true
            this.successMessage = 'Item Saved'
        },
        cancelEdit(){
            this.currentItem = null
            this.showSuccess = false
        },
        loadItems(){
            this.$store.dispatch('loadItems')
        },
        refreshItems(newItems, newMeta, newLinks) {
            this.$store.commit('refreshItems', {newItems, newMeta, newLinkmessage})
            this.showSuccess = true
            this.successMessage = message
        },
    },
    computed: {
        isManager() {
            return this.$store.getters.isManager
        },
        getItems(){
            return this.$store.getters.items
        },
        getItemsMeta(){
            return this.$store.getters.itemsMeta
        },
        getItemsLinks(){
            return this.$store.getters.itemsLinks
        },
    },
    mounted() {
        this.loadItems()
    }
}
</script>

