<template>
    <div>
        <div class="jumbotron">
            <h1>{{title}} <button class="btn btn-warning"><router-link to="newUser">Add new User</router-link></button></h1>
        </div>
        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
        <users-list :users="users" @edit-click="editUser" @delete-click="deleteUser" @message="childMessage" ref="usersListRef"></users-list>
        <user-edit :user="currentUser" @user-saved="savedUser" @user-canceled="cancelEdit" v-if="currentUser"></user-edit>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            title: 'Users',
            showSuccess: false,
            successMessage: '',
            currentUser: null,
            users: [],
        }
    },
    methods: {
        editUser(user){
	            this.currentUser = user;
	            this.showSuccess = false;
	        },
        deleteUser(user){
            axios.delete('api/user/' + user.id)
            .then(response => {
                this.getUsers();
            })
        },
        savedUser(){
            this.currentUser = null;
            this.$refs.usersListRef.editingUser = null;
            this.showSuccess = true;
            this.successMessage = 'User Saved';
        },
        cancelEdit(){
            this.currentUser = null;
            this.$refs.usersListRef.editingUser = null;
            this.showSuccess = false;
        },
        getUsers(){
            axios.get('api/users')
            .then(response=>{
                this.users = response.data.data; 
            });
        },
        childMessage(message){
            this.showSuccess = true;
            this.successMessage = message;
        }        
    },
    mounted() {
        this.getUsers()
    },
}
</script>

