<template>
	<div class="jumbotron">
	    <h2>{{title}} ({{autenticatedUser.email}})</h2>
	    <div class="form-group">
	        <label for="inputName">Full Name</label>
	        <input
	            type="text" class="form-control" v-model="autenticatedUser.name"
	            name="name" id="inputName" 
	            placeholder="name"/>
	    </div>
	    <div class="form-group">
	        <label for="inputName">Username</label>
	        <input
	            type="text" class="form-control" v-model="autenticatedUser.username"
	            name="username" id="inputUsername" 
	            placeholder="Username"/>
	    </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control" v-model="autenticatedUser.password"
                name="password" id="inputPassword"/>
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control" v-model="autenticatedUser.password_confirmation"
                name="password_confirmation" id="password_confirmation"/>
        </div>
        <div>
            <input class="form-data" type="file" accept="image/*"
                @change="imageChanged">
        </div>
        <br>
	    <div class="form-group">
	        <a class="btn btn-primary" @click.prevent="saveUser()">Save</a>
	        <a class="btn btn-light" @click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script>
export default {
    data() {
        return {
            title: 'Edit Account'
        }
    },
    methods: {
        saveUser(){
            axios.patch('api/user/'+this.autenticatedUser.id, this.autenticatedUser)
                .then(response=>{
                    this.$store.dispatch('setAuthUser', this.autenticatedUser)
                    this.$router.push("/dashboard")
                })
        },
        cancelEdit(){
            axios.get('api/user/'+this.autenticatedUser.id)
                .then(response=>{
                    this.$store.dispatch('setAuthUser', this.autenticatedUser)
                    this.$router.push("/dashboard")
                });
        },
        imageChanged(event) {
            let fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])
            fileReader.onload = (event) => {
                this.autenticatedUser.photo_url = event.target.result
            }
        },
    },
    computed: {
        autenticatedUser() {
            return this.$store.getters.getAuthUser
        },
    }
}
</script>
