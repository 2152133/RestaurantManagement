<template>
<div>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <button class="btn btn-primary" @click.prevent="sendManagerMessage">Send Manager message</button>
        <button class="btn btn-dark"><router-link to="profileEdit">Edit Profile</router-link></button>
        <button v-if="isManager" class="btn btn-dark"><router-link to="managerUsers">Users</router-link></button>
        <button v-if="isManager" class="btn btn-dark"><router-link to="tables">Tables</router-link></button>
        <button v-if="isManager" class="btn btn-dark"><router-link to="managerMeals">Meals</router-link></button>
        <button v-if="isManager" class="btn btn-dark"><router-link to="managerInvoices">Invoices</router-link></button>
        <button v-if="isManager" class="btn btn-dark"><router-link to="statistics">Statistics</router-link></button>
    </div>
    <br>
    <div>
        <div class="container" v-if="isShiftActive">
            <h3>Status: {{ shiftStatus }}</h3>
            <br>
            <h3>Service started at: {{ lastShiftStartTime }}</h3>
            <br>
            <h3>Time elapsed from service start: {{ timeElapsed(lastShiftStartTime) }}</h3>
        </div>
        <div class="container" v-else>
            <h3>Status: {{ shiftStatus }}</h3>
            <br>
            <h3>Service finished at: {{ lastShiftEndTime }}</h3>
            <br>
            <h3>Time elapsed from service end: {{ timeElapsed(lastShiftEndTime) }}</h3>
        </div>
    </div>
    <br>
    <button v-if="!isShiftActive" @click.prevent="startShift()" class="btn btn-success">Start Shift</button>
    <button v-else @click.prevent="endShift()" class="btn btn-danger">End Shift</button>
  </div>
</template>
<script>
export default {
    data() {
        return {
            title: "Dashboard",
            numberOfNotifications: 0,
        };
    },
    methods: {
        startShift() {
            this.getAutenticatedUser.shift_active = 1
            let currentDate = new Date()
            currentDate = currentDate.getUTCFullYear() + "-" + (currentDate.getUTCMonth() + 1) + "-" + currentDate.getUTCDate() + " " + currentDate.getUTCHours() + ":" + currentDate.getUTCMinutes() + ":" + currentDate.getUTCSeconds();
            this.getAutenticatedUser.last_shift_start = currentDate
            axios.patch('api/shift/'+this.getAutenticatedUser.id, this.getAutenticatedUser)
                .then(response=>{
                    this.timeElapsed(this.getAutenticatedUser.last_shift_start)
                    this.$store.dispatch('setAuthUser', this.getAutenticatedUser)
                    this.$socket.emit('user_enter', this.getAutenticatedUser)
                })
            
        },
        endShift() {
            this.getAutenticatedUser.shift_active = 0
            let currentDate = new Date()
            currentDate = currentDate.getUTCFullYear() + "-" + (currentDate.getUTCMonth() + 1) + "-" + currentDate.getUTCDate() + " " + currentDate.getUTCHours() + ":" + currentDate.getUTCMinutes() + ":" + currentDate.getUTCSeconds();
            this.getAutenticatedUser.last_shift_end = currentDate
            axios.patch('api/shift/'+this.getAutenticatedUser.id, this.getAutenticatedUser)
                .then(response=>{
                    this.timeElapsed(this.getAutenticatedUser.last_shift_end)
                    this.$store.dispatch('setAuthUser', this.getAutenticatedUser)
                        this.$socket.emit('user_exit', this.getAutenticatedUser)
                })
        },
        timeElapsed(date) {
            let currentDate = new Date()
            let compareDate = new Date(date)
            let difference = Math.abs(currentDate - compareDate);
            let minutes = Math.floor((difference/1000)/60);
            let h = Math.floor(minutes/60)
            let m = minutes%60
            let message = ""
            message += h + " hours "
            message += m + " minutes"
            return message
        },
        sendManagerMessage(){
            let msg = window.prompt('What do you want to say to the managers?')
            console.log('Sending to the server (managers) this message: "' + msg + '"')
            this.$socket.emit('msg_from_client_type_manager', msg, this.getAutenticatedUser);
        }
    },
    computed: {
        getAutenticatedUser() {
            return this.$store.getters.getAuthUser
        },
        isShiftActive() {
            return this.$store.getters.getAuthUser.shift_active
        },
        shiftStatus() {
            return this.isShiftActive ? 'working' : 'not working'
        },
        lastShiftEndTime() {
            return this.$store.getters.getAuthUser.last_shift_end
        },
        lastShiftStartTime() {
            return this.$store.getters.getAuthUser.last_shift_start
        },
        isManager() {
            return this.$store.getters.isManager
        },
    },
}
</script>