<template>
<div>
    <div class="jumbotron">
	    <h2>Edit User</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input type="text" class="form-control"  id="inputName" placeholder="Fullname" 
                name="name"
                v-model="user.name"
                v-validate="'required'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('name')">
                {{ errors.first('name')}}
            </div>
	    </div>
        <div class="form-group">
	        <label for="inputName">Username</label>
	        <input
	            type="text" class="form-control" id="inputUsername" placeholder="Username" 
	            name="username"
                v-model="user.username"
                v-validate="'required'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('username')">
                {{ errors.first('username')}}
            </div>
	    </div>
        <div class="form-group">
            <input type="radio" class="form-group" value="cook" v-model="user.type" name="role" v-validate="'required|included:cook,waiter,cashier,manager'"> Cook
            <br>
            <input type="radio" class="form-group" value="waiter" v-model="user.type"> Waiter
            <br>
            <input type="radio" class="form-group" value="cashier" v-model="user.type"> Cashier
            <br>
            <input type="radio" class="form-group" value="manager" v-model="user.type"> Manager
            <div class="help-block alert alert-danger"
                v-show="errors.has('role')">
                {{ errors.first('role')}}
            </div>
        </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" id="inputEmail" placeholder="Email"
	            name="email"
                v-model="user.email"
                v-validate="'required|email'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('email')">
                {{ errors.first('email')}}
            </div>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-success" @click.prevent="saveUser()">Save</a>
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
            this.$validator.validateAll().then((result) => {
                if(result) {
                    axios.put('api/user/'+this.user.id, this.user)
                    .then(response=>{
                        Object.assign(this.user, response.data.data);
                        this.$emit('user-saved', this.user)
                    });
                }
            })
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
