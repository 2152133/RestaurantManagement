<template>
    <div>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Restaurant Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a><router-link class="nav-item nav-link" to="items">Items</router-link></a>
                </li>
                <li class="nav-item">
                    <a><router-link class="nav-item nav-link" to="orders" v-if="isAuthenticated">Orders</router-link></a>
                </li>
                <li class="nav-item">
                    <a><router-link class="nav-item nav-link" to="invoices" v-if="isAuthenticated">Invoices</router-link></a>
                </li>
                <li class="nav-item">
                    <a><router-link class="nav-item nav-link" to="mealsOfWaiter" v-if="isAuthenticated">MyMeals</router-link></a>
                </li>
                <li class="nav-item">
                    <a><router-link class="nav-item nav-link" to="dashboard" v-if="isAuthenticated">Dashboard</router-link></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="navbar-item">
                    <a v-if="isAuthenticated" class="nav-item nav-link">Status: {{shiftStatus}}</a>
                </li>
                <li class="navbar-item">
                    <router-link class="nav-item nav-link" to="login" v-if="!isAuthenticated">Login</router-link>
                    <router-link class="nav-item nav-link" to="logout" v-else>Logout</router-link>
                </li>
            </ul>
          </div>
        </nav>
    </div>
</template>
<script>
export default {
    computed: {
        shiftStatus() {
            return this.$store.getters.getAuthUser.shift_active ? 'Working' : 'Not working'
        },
        isAuthenticated() {
            return this.$store.getters.isAuthenticated
        },
    }
}
</script>
