<template>
    <div>
        <h2>{{ title }}</h2>
        <div class="card" >
            <strong>Status: </strong>
            <br>
            <strong>Last shift ended at: </strong>{{user.last_shift_end}}
            <br>
            <strong>Time elapsed from last service: </strong>
        </div> 
        <button class="btn btn-success">Start Shift</button>
        <button class="btn btn-danger">End Shift</button>
        <br>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            title: 'Dashboard',
            userLogged: false,
            shift_started: false,
            users: [],
            user :{
                "id": "",
                "name": "",
                "username": "",
                "email": "",
                "email_verified_at": "",
                "type": "",
                "blocked": "",
                "photo_url": "",
                "last_shift_start": "",
                "last_shift_end": "",
                "shift_active": "" 
             }
        }
    },
    methods: {
        shiftToBoolean: function(){
            if(this.user.shift_active == 1){
                this.shift_started=true;
            }
        }
    },
    mounted() {
       
    },
}
</script>
