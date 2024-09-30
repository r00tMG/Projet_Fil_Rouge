<script >
import {computed, onMounted, ref} from "vue";
import axios from "@/axios.js";
import Loader from "@/components/Loader.vue";
import Swal from "sweetalert2";


export default {
  name: 'TableProfile',
  components: {Loader},
  setup(){
    let token = localStorage.getItem('token')
    const data = JSON.parse(localStorage.getItem('data'))
    const roles = data.user.role
    const reservations = ref([])
    const annonces = ref([])
    const searchQuery = ref('')
    //console.log(user.id)
    //console.log(roles)
    const profile = ref({ profiles: [] })
const isLoading = ref(false)

    const fetchReservation = async ()=>{
      const r = await axios.get('/demandes', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      reservations.value = await r.data
      //console.log('Mes réservations:', reservations.value)
    }
    const fetchAnnonce = async ()=>{
      const r = await axios.get('/annonces',{
        headers: {
          'Accept':'application/json',
          'Authorization':`Bearer ${localStorage.getItem('token')}`
        }
      })
      annonces.value = r.data.annonces
      //console.log(annonces.value)
    }

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
      await fetchReservation()
      await fetchAnnonce()
    })

    const filteredAnnonces = computed(() => {
      if (!searchQuery.value) return annonces.value;
      return annonces.value.filter((item) =>{
          return(
            item.destination.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.origin.toLowerCase().includes(searchQuery.value.toLowerCase())
          )
      }
      );
    });
    const filteredReservations = computed(() => {
      if (!searchQuery.value) return reservations.value;
      return reservations.value.filter((item) =>{
            return(
                item.annonce.destination.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                item.annonce.origin.toLowerCase().includes(searchQuery.value.toLowerCase())
            )
          }
      );
    });
    const onDelete = async (id)=>{
      if(confirm('Êtes-vous sûre?')){
        const r = await axios.delete(`/annonces/${id}`,{
          headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
          }
        })
        const rep = await r.data
        console.log(rep)
        await Swal.fire({
          title:'success',
          text:rep.message,
          icon:'success',
          confirmButton:'Ok'
        })
      }
    }
    return{
      profile,
      roles,
      reservations,
      annonces,
      isLoading,
      searchQuery,
      filteredAnnonces,
      filteredReservations,
      onDelete
    }
  }
}

</script>

<template>
  <Loader  :isLoading="isLoading"/>
  <div class="row" v-if="profile.profiles" v-for="profile in profile.profiles">
    <div class="col-12 grid-margin">
      <div class="card mb-4 border border-success">
        <div class="position-relative">
          <figure class="overflow-hidden mb-0 justify-content-center height">
            <img :src="profile.user.storage + '/' + profile.user.photo_profile" class="rounded-top" alt="profile cover">
          </figure>
          <div class="d-flex justify-content-between position-absolute align-items-center w-100 px-2 px-md-4 mt-n4">
            <div>
              <img class="wd-70 rounded-circle border border-success" :src="profile.user.storage + '/' + profile.user.photo_profile" width="40px" height="40px" alt="profile">
              <span class="h4 ms-3 text-dark" v-text="profile.user.name"></span>
            </div>
            <div class="d-none d-md-block">
              <router-link class="btn btn-success  rounded-5"
                  to="/search/annonces"
                  :class="{ active: $route.path === `/profile/${profile.id}/edit` }"
              ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search me-2" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
              </svg>Trouver un GP
              </router-link>
              <router-link class="btn btn-primary ms-2 rounded-5"
                           :to="`/profile/${profile.id}/edit`"
                           :class="{ active: $route.path === `/profile/${profile.id}/edit` }"
              ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square me-2" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
              </svg>Edit profile
              </router-link>
              <router-link class="btn btn-warning ms-2 text-light rounded-5"
                           to="/messages"
                           :class="{ active: $route.path === `/messages` }"
              ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots-fill me-2" viewBox="0 0 16 16">
                <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
              </svg>Chat
              </router-link>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-center p-3 rounded-bottom">

        </div>
      </div>
    </div>
  </div>
  <div v-else>
      <p class="text-light text-center bg-danger p-5 rounded-5 ">Veuillez compléter votre profile</p>
  </div>
  <div class="row profile-body" v-if="profile.profiles" v-for="profile in profile.profiles">
    <!-- left wrapper start -->
    <div class="d-none d-md-block col-md-2  left-wrapper">
      <div class="card rounded border border-success">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title mb-0">About</h6>
            <div class="dropdown">
              <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="git-branch" class="icon-sm me-2"></i> <span class="">Update</span></a>
                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View all</span></a>
              </div>
            </div>
          </div>
          <p>Hi! I'm Amiah the Senior UI Designer at NobleUI. We hope you enjoy the design and quality of Social.</p>
          <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Name:</label>
            <p class="text-muted" v-text="profile.user.name"></p>
          </div>
          <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
            <p class="text-muted" v-text="profile.user.email"></p>
          </div>
          <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Address:</label>
            <p class="text-muted" v-text="profile.address"></p>
          </div>
          <div class="mt-3">
            <label class="tx-11 fw-bolder mb-0 text-uppercase">Job:</label>
            <p class="text-muted" v-text="profile.job"></p>
          </div>
          <div class="mt-3 d-flex social-links">
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
              </svg>
            </a>
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
              </svg>
            </a>
            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- left wrapper end -->
    <!-- middle wrapper start -->
    <div class="col-md-8  middle-wrapper">
      <div class="row bg-success rounded-1">
        <div class="col-md-12  grid-margin" v-if="roles[0].name === 'GP'">
          <div class="d-flex justify-content-between align-items">
            <h4 class="text-light" >Mes annonces</h4>
          </div>
          <div class="bg-white mb-3 p-3 shadow m-auto border-success border rounded" v-if="filteredAnnonces.length !==0" v-for="annonce in filteredAnnonces" :key="annonce.id">
            <div class="card-header bg-white border-0">
              <div class="d-flex justify-content-between align-items">
                <div>
                  <img class="rounded-circle" :src="`${annonce.user.storage}/${annonce.user.photo_profile}`" width="40px" height="40px" alt="Photo Profile">
                  {{ annonce.user.name }}
                </div>
                <div>
                  <p class="text-muted mt-1">Publiée il y a {{ Math.abs(new Date(annonce.date_now).getHours() - new Date(annonce.created_at).getHours()) }}h</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-10">
                <div class="card-body border-success rounded-5 shadow border">
                  <div class="row p-5">
                    <div class="col-md-3">
                      <h4 class="card-title">Départ</h4>
                      <div class="card-title text-success"><strong>{{ new Date(annonce.date_depart).toLocaleDateString() }}</strong></div>
                      <div class="card-title text-success"><strong>{{(new Date(annonce.date_depart).getHours().toString())}}h {{(new Date(annonce.date_depart).getMinutes().toString())}}min</strong></div>
                      <div class="card-title">{{ annonce.origin }}</div>
                    </div>
                    <div class="col-md-6 text-center">
                      <div class="m-auto">
                        <div class="card-title text-success">{{Math.abs((new Date(annonce.date_arrivee).getHours().toString()) - (new Date(annonce.date_depart).getHours().toString()))}}h {{Math.abs((new Date(annonce.date_arrivee).getMinutes().toString()) - (new Date(annonce.date_depart).getMinutes().toString()))}}min</div>
                        <hr class="border-success border-2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <h4 class="card-title">Arrivée</h4>
                      <div class="card-title text-success"><strong>{{ new Date(annonce.date_arrivee).toLocaleDateString() }}</strong></div>
                      <div class="card-title text-success"><strong>{{(new Date(annonce.date_arrivee).getHours().toString())}}h {{(new Date(annonce.date_arrivee).getMinutes().toString())}}min</strong></div>
                      <div class="card-title">{{ annonce.destination }}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 ">
                <h4 class="card-title mt-4">Poids</h4>
                <div class="card-title"><span class="text-muted">Disponible</span></div>
                <div class="card-title"><strong>{{ annonce.kilos_disponibles }} Kg</strong></div>
                <a :href="`/annonces/${annonce.id}`" class="btn btn-sm btn-success mb-1 rounded-4">Réserver</a>
                <button @click="onDelete(annonce.id)" class="btn btn-sm btn-danger rounded-4">Delete</button>
              </div>
            </div>
          </div>
          <div class="bg-white mb-3 p-3 shadow m-auto border-success border rounded" v-else-if="filteredAnnonces.length ===0">
            <div class="container m-auto text-center">
              Aucune annonce n'a été trouvé
            </div>
          </div>
        </div>
        <div class="border border-success">
          <h4 class="text-light">Mes réservations</h4>
          <div class="bg-white mb-3 p-3 shadow m-auto border-success border rounded" v-if="filteredReservations.length !==0" v-for="reservation in filteredReservations" :key="reservation.id">
            <div class="row">
              <div class="col-md-10">
                <div class="card-body border-success rounded-5 shadow border">
                  <div class="row p-5">
                    <div class="col-md-3">
                      <h4 class="card-title">Départ</h4>
                      <div class="card-title text-success"><strong>{{ new Date(reservation.annonce.date_depart).toLocaleDateString() }}</strong></div>
                      <div class="card-title text-success"><strong>{{(new Date(reservation.annonce.date_depart).getHours().toString())}}h {{(new Date(reservation.annonce.date_depart).getMinutes().toString())}}min</strong></div>
                      <div class="card-title">{{ reservation.annonce.origin }}</div>
                    </div>
                    <div class="col-md-6 text-center">
                      <div class="m-auto">
                        <div class="card-title text-success">{{Math.abs((new Date(reservation.annonce.date_arrivee).getHours().toString()) - (new Date(reservation.annonce.date_depart).getHours().toString()))}}h {{Math.abs((new Date(reservation.annonce.date_arrivee).getMinutes().toString()) - (new Date(reservation.annonce.date_depart).getMinutes().toString()))}}min</div>
                        <hr class="border-success border-2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <h4 class="card-title">Arrivée</h4>
                      <div class="card-title text-success"><strong>{{ new Date(reservation.annonce.date_arrivee).toLocaleDateString() }}</strong></div>
                      <div class="card-title text-success"><strong>{{(new Date(reservation.annonce.date_arrivee).getHours().toString())}}h {{(new Date(reservation.annonce.date_arrivee).getMinutes().toString())}}min</strong></div>
                      <div class="card-title">{{ reservation.annonce.destination }}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 ">
                <h4 class="card-title mt-5">Poids</h4>
                <div class="card-title"><span class="text-muted">Réservés</span></div>
                <div class="card-title"><strong>{{ reservation.kilos_demandes }} Kg</strong></div>
                <a :href="`/annonces/${reservation.id}`" class="btn btn-sm btn-success rounded-4">Détails</a>
              </div>
            </div>
          </div>
          <div class="bg-white mb-3 p-3 shadow m-auto border-success border rounded" v-else-if="filteredReservations.length === 0" >
            <div class="container m-auto text-center">
              Aucune réservation n'a été trouvé
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- middle wrapper end -->
    <!-- right wrapper start -->

    <div class="d-none col-md-2 d-xl-block ">
      <div class="row mb-3 " >
        <div class="col-md-12  grid-margin">
          <div class="card rounded border border-success">
            <div class="card-body">

              <div class="srch_bar">
                <div class="stylish-input-group">
                  <input type="text" class="form-control search-bar" v-model="searchQuery" placeholder="Search">
                  <!--                <button class="btn btn-sm btn-success" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                  </button>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-3 " v-if="roles[0].name === 'GP'">
        <div class="col-md-12  grid-margin">
          <div class="card rounded border border-success">
            <div class="card-body">
              <h6 class="card-title mb-4 text-center">Annonces</h6>
              <div class="row ms-0 me-0">
                <p  class="col-md-4 m-auto ps-1 pe-1">
                  <div class="mb-2">
                    <p class="">
                      <span class="step-number me-3">{{annonces.length}}</span>
                    </p>
                  </div>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="card rounded border border-success">
            <div class="card-body">
              <h6 class="card-title mb-4 text-center">Réservations</h6>
              <div class="row ms-0 me-0">
                <p  class="col-md-4 m-auto ps-1 pe-1">
                  <div class="mb-2">
                    <p class="">
                      <span class="step-number me-3">{{reservations.length}}</span>
                    </p>
                  </div>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <p class="text-light text-center bg-danger p-5 rounded-5 ">Aucun utilisateur n'a été trouvé</p>
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