<template>
    <div>
        <br>
        <div class="alert" :class="typeofmsg" v-if="showMessage">             
            <!-- <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button> -->
            <strong>{{ message }}</strong>
        </div>
        <div class="jumbotron">
            <h2>Login</h2>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                    type="email" class="form-control" v-model.trim="user.email"
                    name="email" id="inputEmail"
                    placeholder="Email address"/>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input
                    type="password" class="form-control" v-model="user.password"
                    name="password" id="inputPassword"/>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">    
    export default {
        data: function(){
            return { 
                user: {
                    email:"",
                    password:""
                },
                typeofmsg: "alert-success",
                showMessage: false,
                message: "",
            }
        },
        methods: {
            login() {
                this.showMessage = false;
                axios.post('api/login', this.user)
                    .then(response => {
                        let tokenType = response.data.token_type
                        let token = response.data.access_token
                        let expiration = response.data.expires_in + Date.now()
                        this.$store.commit('setAuthUser', this.user)
                        this.$store.commit('setToken', {token, tokenType, expiration})
                        this.$socket.emit('user_enter', this.$store.state.user);
                        this.typeofmsg = "alert-success";
                        this.message = "User authenticated correctly";
                        this.showMessage = true;
                        setTimeout(() => {
                            this.$router.push("/dashboard")
                        }, 1000);
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Invalid credentials";
                        this.showMessage = true;
                        console.log(error);
                    })
                    .then(response => {
                    axios.get('api/user')
                        .then(response => {
                            let user = response.data
                            this.$store.dispatch('setAuthUser', user)
                        })
                        .catch(error => {
                            console.log(error);
                        })
                })
            },
        },
    }
</script>