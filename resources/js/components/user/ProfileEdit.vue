<template>
	<div class="jumbotron">
	    <h2>Edit User</h2>
	    <div class="form-group">
	        <label for="inputName">Username</label>
	        <input
	            type="text" class="form-control" v-model="user.username"
	            name="username" id="inputUsername" 
	            placeholder="Username"/>
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" v-model="user.email"
	            name="email" id="inputEmail"
	            placeholder="Email address"/>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
	    </div>
        <div>
            <input class="form-data" type="file" accept="image/*"
                @change="onImageSelected">
        </div>
	</div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
        }
    },
    methods: {
        saveUser(){
            axios.patch('api/user/'+this.user.id, this.user)
                .then(response=>{
                    Object.assign(this.user, response.data.data);
                    this.$emit('user-saved', this.user)
                });
        },
        onImageSelected(event) {
            this.user.photo_url = String(event.target.files[0].name)
        },
        getCurrentUser() {
            this.user = this.$store.state.user;
        },
    },
    mounted() {
        this.getCurrentUser()
    }
}
</script>
