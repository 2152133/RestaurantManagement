<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
        <items-list :items="items" @edit-click="editItem" @delete-click="deleteItem" @message="childMessage" ref="itemsListRef"></items-list>
        <item-edit :item="currentItem" @item-saved="savedItem" @item-canceled="cancelEdit" v-if="currentItem"></item-edit>
    </div>
</template>

<script>
export default {
    data: function() {
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
	            this.currentItem = item;
	            this.showSuccess = false;
	        },
        deleteItem(item){
            axios.delete('api/item/' + item.id)
            .then(response => {
                this.getItems();
            })
        },
        savedItem: function(){
            this.currentItem = null;
            this.$refs.itemsListRef.editingitem = null;
            this.showSuccess = true;
            this.successMessage = 'Item Saved';
        },
        cancelEdit: function(){
            this.currentItem = null;
            this.$refs.itemsListRef.editingitem = null;
            this.showSuccess = false;
        },
        getItems: function(){
            axios.get('api/items')
            .then(response=>{
                this.items = response.data.data; 
            });
        },
        childMessage: function(message){
            this.showSuccess = true;
            this.successMessage = message;
        }        
    },
    mounted() {
        this.getItems()
    },
}
</script>

