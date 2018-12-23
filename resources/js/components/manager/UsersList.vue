<template>
<div>
    <nav aria-label="Page navigation example">
        
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getusers(pagination.prev_page_url)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getusers(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody v-for="user in users" v-bind:key="user.id">
            <td><img v-bind:src="userImageURL(user.photo_url)" height="80" width="100"></td>
            <td>{{ user.name }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>
            <td> 
                <a @click.prevent="edituser(user)" class="btn btn-sm btn-primary">Edit</a>
                <a @click.prevent="deleteuser(user)" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    props: ['users'],
    data() {
        return {
            editinguser: null,
            pagination: {},
        }
    },
    methods: {
        edituser(user){
            this.editinguser = user;
            this.$emit('edit-click', user);
        },		
        deleteuser(user){
            this.editinguser = null;
            this.$emit('delete-click', user);
        },
        userImageURL(photo) {
            return "storage/profiles/" + String(photo);
        },
        compactDescription(text) {
            return text.length > 100 ? text.substr( 0, 98 )+'...' : text;
        },
         getusers(url) {    
            let page_url = url || '/api/users'
            axios.get(page_url)
                .then(response => {
                    Object.assign(this.users, response.data.data);
                    this.makePagination(response.data.meta, response.data.links)
                })
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
    },
    mounted() {
        this.getusers()
    },
}
</script>
