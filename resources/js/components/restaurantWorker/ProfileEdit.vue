<template>
	<div class="jumbotron">
	    <h2>{{title}} ({{autenticatedUser.email}})</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input type="text" class="form-control"  id="inputName" placeholder="Fullname" 
                name="name"
                v-model="autenticatedUser.name"
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
                v-model="autenticatedUser.username"
                v-validate="'required'"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('username')">
                {{ errors.first('username')}}
            </div>
	    </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control" v-model="autenticatedUser.password"
                name="password" id="inputPassword" 
                v-validate="'required'" ref="password"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('password')">
                {{ errors.first('password')}}
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword">Confirm Password</label>
            <input
                type="password" class="form-control" v-model="autenticatedUser.password_confirmation"
                name="password_confirmation" id="password_confirmation" 
                v-validate="'required|confirmed:password'" data-vv-as="password"/>
            <div class="help-block alert alert-danger"
                v-show="errors.has('password_confirmation')">
                {{ errors.first('password_confirmation')}}
            </div>
        </div>
        <div>
            <input class="form-data" type="file" name="image" accept="image/*"
                @change="imageChanged" 
                v-validate="'image'">
            <div class="help-block alert alert-danger"
                v-show="errors.has('image')">
                {{ errors.first('image')}}
            </div>
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
            this.$validator.validateAll().then((result) => {
                if(result) {
                    this.autenticatedUser.type = this.$store.getters.getAuthUser.type 
                    axios.patch('api/user/'+this.autenticatedUser.id, this.autenticatedUser)
                        .then(response=>{
                            this.$store.dispatch('setAuthUser', this.autenticatedUser)
                            this.$router.push("/dashboard")
                        })
                }
            })
        },
        cancelEdit(){
            axios.get('api/user/'+this.autenticatedUser.id)
                .then(response=>{
                    this.$store.dispatch('setAuthUser', response.data.data)
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
