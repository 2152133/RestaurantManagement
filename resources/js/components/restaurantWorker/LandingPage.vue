<template>
<div>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <button class="btn btn-success" @click.prevent="sendMessageToActiveManagers">Send Manager message</button>
        <button class="btn btn-warning"><router-link to="profileEdit">Edit Profile</router-link></button>
        <button class="btn btn-warning"><router-link to="users">All Users</router-link></button>
        <button class="btn btn-warning"><router-link to="notifications">Notifications: {{numberOfNotifications}}</router-link></button>
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
    <br>
  </div>
</template>
<script>
export default {
    data() {
        return {
            title: "Dashboard",
            numberOfNotifications: 0,
            managers: [],
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
                    this.getAutenticatedUser
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
                    this.getAutenticatedUser
                })
        },
        sendMessageToActiveManagers(){
            let msg = window.prompt('What do you want to say to the managers?');
            this.managers.forEach(manager => {
                console.log('Sending Message "' + msg + '" to "' + manager.name + '"');
                this.$socket.emit('privateMessage', msg, this.$store.state.user, manager);
            });
        },
        getActiveManagers() {
             axios.get("/api/managers")
                .then(response => {
                    response.data.forEach(manager => {
                        if (this.getAutenticatedUser.id != manager.id && manager.shift_active) {
                            this.managers.push(manager)
                        }
                    })
            })
        },
        timeElapsed(date) {
            let currentDate = new Date()
            //currentDate = currentDate.getUTCFullYear() + "-" + (currentDate.getUTCMonth() + 1) + "-" + currentDate.getUTCDate() + " " + currentDate.getUTCHours() + ":" + currentDate.getUTCMinutes() + ":" + currentDate.getUTCSeconds();
            let compareDate = new Date(date)
            //endShiftDate = endShiftDate.getUTCFullYear() + "-" + (endShiftDate.getUTCMonth() + 1) + "-" + endShiftDate.getUTCDate() + " " + endShiftDate.getUTCHours() + ":" + endShiftDate.getUTCMinutes() + ":" + endShiftDate.getUTCSeconds();
            //let diff = Math.floor(currentDate.getTime() - compareDate.getTime());
            //let day = 1000 * 60 * 60 * 24;
            //let days = Math.floor(diff/day);
            //let months = Math.floor(days/31);
            //let years = Math.floor(months/12);
            let difference = Math.abs(currentDate - compareDate);
            let minutes = Math.floor((difference/1000)/60);
            let h = Math.floor(minutes/60)
            let m = minutes%60
            let message = ""//endShiftDate.toDateString();
            // message += " was "
            //message += years + " years " 
            //message += months + " months "
            //message += days + " days "
            message += h + " hours "
            message += m + " minutes"
            //return (hour + " hours " + minuts + " minutes")
            //return minutes
            return message
        },
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
        
    },
    mounted() {
        this.getActiveManagers()
    },
}
</script>