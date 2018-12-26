<template>
<div>
    <nav aria-label="Page navigation example">
        <div>
            <a class="btn btn-primary" @click.prevent="getUsers()">All</a>
            <a class="btn btn-primary" @click.prevent="getBlocked(1)">Blocked</a>
            <a class="btn btn-primary" @click.prevent="getBlocked(0)">Not Blocked</a>
            <a class="btn btn-primary" @click.prevent="getDeleted(1)">Deleted</a>
            <a class="btn btn-primary" @click.prevent="getDeleted(0)">Not Deleted</a>
        </div>
        <br>
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getUsers(pagination.prev_page_url)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getUsers(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Deleted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody v-for="user in users" v-bind:key="user.id">
            <td><img v-bind:src="userImageURL(user.photo_url)" height="80" width="100"></td>
            <td>{{ user.name }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.type }}</td>
            <td>{{ user.deleted_at ? 'Yes':'No' }}</td>
            <td v-if="!user.deleted_at"> 
                <a @click.prevent="edituser(user)" class="btn btn-sm btn-primary">Edit</a>
                <div v-if="!isAuthUser(user)">
                    <a @click.prevent="deleteUser(user)" class="btn btn-sm btn-danger">Delete</a>
                    <a v-if="!user.blocked" @click.prevent="blockUser(user)" class="btn btn-sm btn-danger">Block</a>
                    <a v-if="user.blocked" @click.prevent="unblockUser(user)" class="btn btn-sm btn-danger">Unblock</a>
                </div>
            </td>
            <td v-else>
                <a disabled class="btn btn-sm btn-primary">Edit</a>
                <a disabled class="btn btn-sm btn-danger">Delete</a>
                <a disabled class="btn btn-sm btn-danger">Block</a>
                <a disabled class="btn btn-sm btn-danger">Unblock</a>
            </td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    //props: ['users'],
    data() {
        return {
            users: {},
            editinguser: null,
            pagination: {},
        }
    },
    methods: {
        edituser(user){
            this.editinguser = user;
            this.$emit('edit-click', user);
            
        },		
        deleteUser(user){
            this.editinguser = null;
            axios.delete('api/user/' + user.id)
            .then(response => {
                this.getUsers();
            })
            //this.$emit('delete-click', user);
        },
        userImageURL(photo) {
            return "storage/profiles/" + String(photo);
        },
        compactDescription(text) {
            return text.length > 100 ? text.substr( 0, 98 )+'...' : text;
        },
        getUsers(url) {    
            let page_url = url || '/api/users'
            axios.get(page_url)
                .then(response => {
                    Object.assign(this.users, response.data.data);
                    this.makePagination(response.data.meta, response.data.links)
                })
        },
        getBlocked(status){
            axios.get('api/users/blocked/' + status)
            .then(response=>{
                Object.assign(this.users, response.data.data);
                this.makePagination(response.data.meta, response.data.links)
            });
        },
        getDeleted(status){
            axios.get('api/users/deleted/' + status)
            .then(response=>{
                Object.assign(this.users, response.data.data);
                this.makePagination(response.data.meta, response.data.links)
            });
        },
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
            }
            this.pagination = pagination
        },
        isAuthUser(user) {
            return user.email == this.$store.getters.getAuthUser.email ? true : false
        },
        blockUser(user) {
            this.editinguser = null;
            axios.patch('api/user/block/' + user.id)
            .then(response => {
                this.getUsers()
            })
        },
        unblockUser(user) {
            this.editinguser = null;
            axios.patch('api/user/unblock/' + user.id)
            .then(response => {
                this.getUsers()
            })
        }
    },
    mounted() {
        this.getUsers()
    },
}
</script>
