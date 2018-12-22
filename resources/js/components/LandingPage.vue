<template>
<div>
    <div>
        <div class="jumbotron">
            <h1>{{title}}</h1>
        </div>
        <button type="button" class="btn btn-warning" style="float:right">
            <router-link :to="{name: 'notifications'}">Notifications</router-link>
            <span class="badge badge-light">{{numberOfNotifications}}</span>
        </button>
    </div>
    <br>
    <br>
            <profile-edit></profile-edit>

    <div>
        <div class="container" v-if="isShiftActive()">
            <h3>Status:</h3>
            {{isWorkingMessage}}
            <br>
            <h3>Service started at:</h3>
            {{ user.last_shift_start }}
            <br>
            <h3>Time elapsed from service start:</h3>
        </div>
        <div class="container" v-else>
            <h3>Status:</h3>
            {{isWorkingMessage}}
            <br>
            <h3>Service finished at:</h3>
            {{ user.last_shift_end }}
            <br>
            <h3>Time elapsed from service end:</h3>
        </div>
    </div>
    <br>
    <button v-on:click.prevent="startShift()" class="btn btn-success">Start Shift</button>
    <button v-if="shift_started" v-on:click.prevent="endShift()" class="btn btn-danger">End Shift</button>
    <br>
  </div>
</template>
<script>
module.exports = {
  data: function() {
    return {
      title: "Dashboard",
      numberOfNotifications: 0,
      userLogged: false,
      shift_started: false,
      isWorkingMessage: "",
      diferenceBetweenEndServiceAndNow: "",
      diferenceBetweenShiftBeginAndNow: "",
      user: {
        id: "",
        name: "",
        username: "",
        email: "",
        email_verified_at: "",
        type: "",
        blocked: "",
        photo_url: "",
        last_shift_start: "",
        last_shift_end: "",
        shift_active: ""
      }
    };
  },
  methods: {
    startShift: function() {
      if (!isShiftActive()) {
        this.user.shift_active = 1;
        this.user.last_shift_start =
          Date.getUTCFullYear() +
          "/" +
          (Date.getUTCMonth() + 1) +
          "/" +
          Date.getUTCDate() +
          " " +
          Date.getUTCHours() +
          ":" +
          Date.getUTCMinutes() +
          ":" +
          Date.getUTCSeconds();
        //Update times
        this.isWorkingMessage = "Working";
      }
    },
    endShift: function() {
      if (isShiftActive()) {
        this.user.shift_active = 0;
        this.user.last_shift_end =
          Date.getUTCFullYear() +
          "/" +
          (Date.getUTCMonth() + 1) +
          "/" +
          Date.getUTCDate() +
          " " +
          Date.getUTCHours() +
          ":" +
          Date.getUTCMinutes() +
          ":" +
          Date.getUTCSeconds();
        //Update Times
        this.isWorkingMessage = "Not Working";
      }
    },
    isShiftActive: function() {
      if (this.user.shift_active == 1) {
        this.shift_started = true;
      } else {
        this.shift_started = false;
      }
    },
    getDuration: function(date1, date2) {}
  },
  mounted() {}
};
</script>



