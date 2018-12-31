<template>
<div>
    <div class="alert alert-success" v-if="showSuccess">			 
        <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
        <strong>{{ successMessage }}</strong>
    </div>
    <div class="jumbotron">
	    <h2>Add Item</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="item.name"
	            name="name" id="inputName" 
	            placeholder="Name"
                v-validate="'required'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('name')">
                {{ errors.first('name')}}
            </div>
	    </div>
        <div class="form-group">
            <input type="radio" class="form-group" value="dish" name="type" v-validate="'required|included:dish,drink'" v-model="item.type"> Dish
            <br>
            <input type="radio" class="form-group" value="drink" v-model="item.type"> Drink
            <div class="help-block alert alert-danger"
                v-show="errors.has('type')">
                {{ errors.first('type')}}
            </div>
        </div>
	    <div class="form-group">
	        <label for="inputDescription">Description</label>
	        <input
	            type="text" class="form-control" v-model="item.description"
	            name="description" id="inputDescription"
	            placeholder="Description"
                v-validate="'required'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('description')">
                {{ errors.first('description')}}
            </div>
	    </div>
	    <div class="form-group">
	        <label for="inputPrice">Price</label>
	        <input
	            type="number" class="form-control" v-model="item.price"
	            name="price" id="inputPrice" step="0.50"
	            placeholder="Price"
                v-validate="'required|decimal:2|max_value:100|min_value:0'">
            <div class="help-block alert alert-danger"
                v-show="errors.has('price')">
                {{ errors.first('price')}}
            </div>
	    </div>
        <div>
            <input class="form-data" type="file" name="image" accept="image/*"
                @change="imageChanged"
                v-validate="'required|image'">
            <div class="help-block alert alert-danger"
                v-show="errors.has('image')">
                {{ errors.first('image')}}
            </div>
        </div>
        <br>
	    <div class="form-group">
	        <a class="btn btn-success" @click.prevent="saveItem()">Save</a>
	        <a class="btn btn-light" @click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</div>
</template>

<script>
export default {
    data() {
        return {
            showSuccess: false,
            successMessage: '',
            item: {
                name: '',
                type: '',
                description: '',
                price: '',
                photo_url: null
            }
        }
    },
    methods: {
        saveItem(){
            this.$validator.validateAll().then((result) => {
                if(result) {
                    axios.post('api/item', this.item)
                    .then(response => {
                        Object.assign(this.item, response.data.data);
                        this.$emit('item-saved', this.item)
                        this.showSuccess = true;
                        this.successMessage = 'Item Created';
                        this.$router.push("/items")
                    })
                }
            })
        },
        cancelEdit(){
            this.item = {}
            this.showSuccess = false;
            this.$router.push("/items")
        },
        imageChanged(event) {
            let fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])
            fileReader.onload = (event) => {
                this.item.photo_url = event.target.result
            }
        }
    },
}
</script>
