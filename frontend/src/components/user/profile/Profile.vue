<script setup>
import {onMounted, ref} from 'vue'
import axios from "@/axios.js";
import Navbar from '@/components/Navbar.vue'
import Loader from "@/components/Loader.vue";

let token = localStorage.getItem('token')
const data = JSON.parse(localStorage.getItem('data'))
//console.log(data.user.role)
const roles = data.user.role
//console.log(user.id)
const isLoading = ref(false);
const profile = ref({profiles: []})
onMounted(async () => {
  try {
    const response = await axios.get(`/profiles`, {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })
    profile.value = await response.data
    console.log(profile.value.profiles.length)
    /*if (profile.value.profiles.length > 0)
    {
      await router.push('/profile')
    }else{
      await router.push('/create/profile')
    }*/
  } catch (error) {
    console.error('Erreur lors de la récupération des profiles:', error)
  }
})
</script>

<template>
  <!-- Navbar top -->
  <Loader  :isLoading="isLoading"/>
  <div v-if="roles" v-for="role in roles" class="">
    <Navbar/>
    <div class="container-fluid px-0">
      <div class="row">
        <div class="col-md-2 sidebar" v-if="profile.profiles.length > 0" v-for="profile in profile.profiles">
          <div class="sidenav-url ">
            <div class="url">
              <p class="text-center py-3 fs-4">
                Dashboard
              </p>
              <hr class="mb-5">
            </div>
            <div class="url">
              <router-link to="/profile" class="fs-5" :class="{ active: $route.path === `/profile/index` }">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                     class="bi bi-person-circle mb-1 me-2" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                  <path fill-rule="evenodd"
                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
                Profile
              </router-link>
            </div>
            <div class="url" v-for="roleName in profile.user.roles">
              <router-link to="/annonces/index" class="fs-5" :class="{ active: $route.path === `/annonces/index` }"
                           v-if="roleName.name === 'GP' ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                     class="bi bi-folder-plus mb-1 me-2" viewBox="0 0 16 16">
                  <path
                      d="m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z"/>
                  <path
                      d="M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5"/>
                </svg>
                Annonces
              </router-link>
            </div>
            <div class="url" v-for="roleName in profile.user.roles">
              <router-link to="/demandes/index" class="fs-5" :class="{ active: $route.path === `/demandes/index` }"
                           v-if="roleName.name === 'GP' || roleName.name === 'Client' || roleName.name === 'admin'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                     class="bi bi-bookmarks mb-1 me-2" viewBox="0 0 16 16">
                  <path
                      d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1z"/>
                  <path
                      d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1"/>
                </svg>
                Réservations
              </router-link>
            </div>
          </div>
        </div>
        <div class="col-md-10 bg-white border border-success p-2">
          <router-view/>
        </div>
      </div>
    </div>
    <!--<h2>SOCIAL MEDIA</h2>-->
    <div class="card">
      <div class="card-body">
        <i class="fa fa-pen fa-xs edit"></i>
        <div class="social-media m-auto">
          <span class="fa-stack fa-sm">
            <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fab fa-facebook fa-stack-1x fa-inverse"></i>
          </span>
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
    </div>
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
  /*text-align: center;*/
}

.url hr {
  margin-left: 20%;
  width: 60%;
}

.url a {
  color: #ebeced;
  display: block;
  font-size: 20px;
  margin: 10px 0;
  padding: 6px 8px;
  text-decoration: none;
}

.url a:hover, .url .active {
  background-color: #58a964;
  border-radius: 28px;
  color: #f8f5f5;
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