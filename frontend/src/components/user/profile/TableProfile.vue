<script >
import {onMounted, ref} from "vue";
import axios from "@/axios.js";

export default {
  name: 'TableProfile',
  setup(){
    let token = localStorage.getItem('token')
    const data = JSON.parse(localStorage.getItem('data'))
    const roles = data.user.role
    //console.log(user.id)
    //console.log(roles)
    const profile = ref({ profiles: [] })

    onMounted(async () => {
      try {
        const response = await axios.get(`/profiles`, {
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        })
        profile.value = await response.data
        //console.log(profile.value)
      } catch (error) {
        console.error('Erreur lors de la récupération des profils:', error)
      }

    })
    return{
      profile,
      roles
    }
  }
}

</script>

<template>
  <div class="my-5" v-if="profile.profiles" v-for="profile in profile.profiles">
    <h2>IDENTITY</h2>
    <div class="card shadow rounded-1" v-if="roles" v-for="role in roles">
      <div class="card-body table-responsive" v-for="permission in role.permission" >
        <table class="" v-if="permission.name === 'profile-read'">
          <router-link
              :to="`/profile/${profile.id}/edit`"
              :class="{ active: $route.path === `/profile/${profile.id}/edit` }"
          ><i class="fa fa-pen fa-xs edit text-dark"></i>
          </router-link>
          <tbody>
          <tr>
            <td>Name</td>
            <td>:</td>
            <td>{{profile.user.name}}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>:</td>
            <td> {{profile.user.email}}</td>
          </tr>
          <tr>
            <td>Address</td>
            <td>:</td>
            <td>{{ profile.address }}</td>
          </tr>
          <tr>
            <td>Hobbies</td>
            <td>:</td>
            <td>{{ profile.hobbies }}</td>
          </tr>
          <tr>
            <td>Job</td>
            <td>:</td>
            <td>{{ profile.job }}</td>
          </tr>
          <tr>
            <td>Skill</td>
            <td>:</td>
            <td>{{ profile.skill }}</td>
          </tr>
          </tbody>
        </table>
<!--        <table v-else>
          <thead class="text-danger text-center">Vous n'êtes pas autorisé à voir le contenu de cette section</thead>
        </table>-->
      </div>

    </div>

    <!--   <h2>SOCIAL MEDIA</h2>
            <div class="card">
              <div class="card-body">
                <i class="fa fa-pen fa-xs edit"></i>
                <div class="social-media">
                            <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>Edit
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                            </span>
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-instagram fa-stack-1x fa-inverse"></i>
                            </span>
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-invision fa-stack-1x fa-inverse"></i>
                            </span>
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                            </span>
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-whatsapp fa-stack-1x fa-inverse"></i>
                            </span>
                  <span class="fa-stack fa-sm">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-snapchat fa-stack-1x fa-inverse"></i>
                            </span>
                </div>
              </div>
            </div>-->
  </div>

</template>

<style scoped>
/* Import Font Dancing Script */
@import url(https://fonts.googleapis.com/css?family=Dancing+Script);

* {
  margin: 0;
}

body {
  background-color: #e8f5ff;
  font-family: Arial;
  overflow: hidden;
}

/* NavbarTop */
.navbar-top {
  background-color: #fff;
  color: #333;
  box-shadow: 0px 4px 8px 0px grey;
  height: 70px;
}

.title {
  font-family: 'Dancing Script', cursive;
  padding-top: 15px;
  position: absolute;
  left: 45%;
}

.navbar-top ul {
  float: right;
  list-style-type: none;
  margin: 0;
  overflow: hidden;
  padding: 18px 50px 0 40px;
}

.navbar-top ul li {
  float: left;
}

.navbar-top ul li a {
  color: #333;
  padding: 14px 16px;
  text-align: center;
  text-decoration: none;
}

.icon-count {
  background-color: #ff0000;
  color: #fff;
  float: right;
  font-size: 11px;
  left: -25px;
  padding: 2px;
  position: relative;
}

/* End */

/* Sidenav */
.sidenav {
  background-color: #fff;
  color: #333;
  border-bottom-right-radius: 25px;
  height: 86%;
  left: 0;
  overflow-x: hidden;
  padding-top: 20px;
  position: absolute;
  top: 70px;
  width: 250px;
}

.profile {
  margin-bottom: 20px;
  margin-top: -12px;
  text-align: center;
}

.profile img {
  border-radius: 50%;
  box-shadow: 0px 0px 5px 1px grey;
}

.name {
  font-size: 20px;
  font-weight: bold;
  padding-top: 20px;
}

.job {
  font-size: 16px;
  font-weight: bold;
  padding-top: 10px;
}

.url, hr {
  text-align: center;
}

.url hr {
  margin-left: 20%;
  width: 60%;
}

.url a {
  color: #818181;
  display: block;
  font-size: 20px;
  margin: 10px 0;
  padding: 6px 8px;
  text-decoration: none;
}

.url a:hover, .url .active {
  background-color: #e8f5ff;
  border-radius: 28px;
  color: #000;
  margin-left: 14%;
  width: 65%;
}

/* End */

/* Main */
.main {
  margin-top: 2%;
  margin-left: 29%;
  font-size: 28px;
  padding: 0 10px;
  width: 58%;
}

.main h2 {
  color: #333;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 24px;
  margin-bottom: 10px;
}

.main .card {
  background-color: #fff;
  border-radius: 18px;
  box-shadow: 1px 1px 8px 0 grey;
  height: auto;
  margin-bottom: 20px;
  padding: 20px 0 20px 50px;
}

.main .card table {
  border: none;
  font-size: 16px;
  height: 270px;
  width: 80%;
}

.edit {
  position: absolute;
  color: #e7e7e8;
  right: 14%;
}

.social-media {
  text-align: center;
  width: 90%;
}

.social-media span {
  margin: 0 10px;
}

.fa-facebook:hover {
  color: #4267b3 !important;
}

.fa-twitter:hover {
  color: #1da1f2 !important;
}

.fa-instagram:hover {
  color: #ce2b94 !important;
}

.fa-invision:hover {
  color: #f83263 !important;
}

.fa-github:hover {
  color: #161414 !important;
}

.fa-whatsapp:hover {
  color: #25d366 !important;
}

.fa-snapchat:hover {
  color: #fffb01 !important;
}

/* End */
</style>