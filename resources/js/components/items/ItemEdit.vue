<template>
<div>
    <div class="jumbotron">
	    <h2>Edit Item</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="item.name"
	            name="name" id="inputName" 
	            placeholder="Fullname"/>
	    </div>
        <div class="form-group">
            <input 
                type="radio" class="form-group" value="dish" v-model="item.type"> Dish
            <br>
            <input 
                type="radio" class="form-group" value="drink" v-model="item.type"> Drink
        </div>
	    <div class="form-group">
	        <label for="inputDescription">Description</label>
	        <input
	            type="text" class="form-control" v-model="item.description"
	            name="description" id="inputDescription"
	            placeholder="Description"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPrice">Price</label>
	        <input
	            type="number" class="form-control" v-model="item.price"
	            name="price" id="inputPrice" step="0.01"
	            placeholder="Price"/>
	    </div>
        <div>
            <input class="form-data" type="file" accept="image/*"
                @change="imageChanged">
        </div>
        <br>
	    <div class="form-group">
	        <a class="btn btn-primary" @click.prevent="saveItem()">Save</a>
	        <a class="btn btn-light" @click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</div>
</template>

<script>
export default {
    props: ['item'],
    methods: {
        saveItem(){
            axios.patch('api/item/'+this.item.id, this.item)
                .then(response=>{
                    Object.assign(this.item, response.data.data);
                    this.$emit('item-saved', this.item)
                });
        },
        cancelEdit(){
            axios.get('api/item/'+this.item.id)
                .then(response=>{
                    Object.assign(this.item, response.data.data);
                    this.$emit('item-canceled', this.item);
                });
        },
        imageChanged(event) {
            let fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])
            fileReader.onload = (event) => {
                this.item.photo_url = event.target.result
            }
        },

    },

}
</script>
