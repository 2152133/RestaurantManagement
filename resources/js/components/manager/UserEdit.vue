<template>
<div>
    <div class="jumbotron">
	    <h2>Edit user</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="user.name"
	            name="name" id="inputName" 
	            placeholder="Fullname"/>
	    </div>
        <div class="form-group">
	        <label for="inputName">Username</label>
	        <input
	            type="text" class="form-control" v-model="user.username"
	            name="username" id="inputUsername" 
	            placeholder="Username"/>
	    </div>
        <div class="form-group">
            <input type="radio" class="form-group" value="cook" v-model="user.type"> Cook
            <br>
            <input type="radio" class="form-group" value="waiter" v-model="user.type"> Waiter
            <br>
            <input type="radio" class="form-group" value="cashier" v-model="user.type"> Cashier
            <br>
            <input type="radio" class="form-group" value="manager" v-model="user.type"> Manager
        </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" v-model="user.email"
	            name="description" id="inputDescription"
	            placeholder="Email"/>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-primary" @click.prevent="saveUser()">Save</a>
	        <a class="btn btn-light" @click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</div>
</template>

<script>
export default {
    props: ['user'],
    methods: {
        saveUser(){
            axios.put('api/user/'+this.user.id, this.user)
                .then(response=>{
                    Object.assign(this.user, response.data.data);
                    this.$emit('user-saved', this.user)
                });
        },
        cancelEdit(){
            axios.get('api/user/'+this.user.id)
                .then(response=>{
                    Object.assign(this.user, response.data.data);
                    this.$emit('user-canceled', this.user);
                });
        },
    },
}
</script>
