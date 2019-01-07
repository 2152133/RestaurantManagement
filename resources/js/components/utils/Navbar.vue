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
                <li v-if="isCook" class="nav-item">
                    <a><router-link class="nav-item nav-link" to="orders">Orders</router-link></a>
                </li>
                <li v-if="isCashier" class="nav-item">
                    <a><router-link class="nav-item nav-link" to="invoices">Invoices</router-link></a>
                </li>
                <li v-if="isWaiter" class="nav-item">
                    <a><router-link class="nav-item nav-link" to="mealsOfWaiter">MyMeals</router-link></a>
                </li>
                <li v-if="isAuthenticated" class="nav-item">
                    <a><router-link class="nav-item nav-link" to="dashboard">Dashboard</router-link></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li v-if="isAuthenticated" class="navbar-item">
                    <a class="nav-item nav-link">{{loggedUser.name}}: {{shiftStatus}}</a>
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
        loggedUser() {
            return this.$store.getters.getAuthUser
        },
        isAuthenticated() {
            return this.$store.getters.isAuthenticated
        },
        isCook() {
            return this.$store.getters.isCook
        },
        isWaiter() {
            return this.$store.getters.isWaiter
        },
        isCashier() {
            return this.$store.getters.isCashier
        },
        isManager() {
            return this.$store.getters.isManager
        }
    }
}
</script>
