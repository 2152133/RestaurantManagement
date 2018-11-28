<template>
    <div>
        <h2>{{ title }}</h2>
        <form @submit.prevent="saveItem()" class="mb-3">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Name"
                v-model="item.name">
            </div>
            <div class="form-group">
                <input class="form-group" type="radio" value="dish" v-model="item.type"> Dish
                <br>
                <input class="form-group" type="radio" value="drink" v-model="item.type"> Drink
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Description"
                v-model="item.description"></textarea>
            </div>
            <div class="form-group">
                <input class="form-control" type="number" step="0.01" placeholder="Price"
                v-model="item.price">
            </div>
            <div>
                <input class="form-data" type="file" accept="image/*"
                @change="onImageSelected">
            </div>
            <br>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
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
        <div class="card card-body mb-2" v-for="item in items" v-bind:key="item.id">
            <div>
                <h3>{{ item.name }}</h3>
                <img v-bind:src="itemImageURL(item.photo_url)" height="200" width="200">
            </div>
            <p>Descricao: {{ compactDescription(item.description) }}</p>            
            <p>Preco: {{ item.price }} €</p>
            <hr>
            <button @click="editItem(item)" class="btn btn-warning">Edit</button>
            <button @click="deleteItem(item.id)" class="btn btn-danger">Delete</button>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            title: 'Items',
            items: [],
            item: {
                "id": '',
                "name": '',
                "type": '',
                "description": '',
                "photo_url": '',
                "price": '',
            },
            pagination: {},
            edit: false,
        }
    },
    methods: {
        getItems: function(url) {    
            let page_url = url || '/api/items'
            axios.get(page_url)
            .then(response => {
                Object.assign(this.items, response.data.data);
                this.makePagination(response.data.meta, response.data.links)
            })
            .catch(error => console.log(error))
        },
        makePagination: function(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
            }
            this.pagination = pagination
        },
        itemImageURL: function (photo) {
            return "storage/items/" + String(photo);
        },
        deleteItem: function(id) {
            axios.delete('api/item/' + id)
            .then(response => {
                this.getItems();
            })
            .catch(error => console.log(error))
        },
        onImageSelected: function(event) {
            this.item.photo_url = String(event.target.files[0].name)
        },
        imageNullCheck: function() {
            this.item.photo_url = this.item.photo_url.length != 0 ? this.item.photo_url : "null"
        },
        saveItem: function() {
            this.imageNullCheck()
            if (this.edit === true) {
                // Edit item
                axios.patch('api/item/' + this.item.id, this.item)
                .then(response=>{
                    rhis.item = {}
                    this.edit = false
                    this.getItems()
                })
                .catch(error => console.log(error)) 
            }else {
                // Add new item
                axios.post('api/item', this.item)
                .then(response=>{
                    this.item = {}
                    this.getItems()
                })
                .catch(error => console.log(error)) 
            }
        },
        editItem: function(item) {
            this.item = Object.assign({}, item);
            this.edit = true
        },
        compactDescription: function(text) {
            // Limit text size
            return text.length > 100 ? text.substr( 0, 98 )+'...' : text;
        },
    },
    mounted() {
        this.getItems()
    },
}
</script>
