<script setup>
import FooterHome from "@/components/user/home/FooterHome.vue";
import {computed, onMounted, ref} from "vue";
import axios from "@/axios.js";
  const annonces = ref([])
const searchDepart = ref('')
const searchArrivee= ref('')

 onMounted(async () => {
  const r = await axios.get('/annonce_on_home')
   annonces.value = await r.data.annonces
})
const filteredAnnonces = computed(() => {
  return annonces.value.filter((annonce) => {
    return (
        //annonce.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        annonce.date_depart.toLowerCase().includes(searchDepart.value.toLowerCase()) ||
        annonce.date_arrivee.toLowerCase().includes(searchArrivee.value.toLowerCase())
    )
  })
})

</script>

<template>
  <main>
    <div class="corde rounded-circle">
    </div>
    <div class="container-fluid">
      <div class="container  m-5 p-5">
        <div class="w-75 m-auto m-5 p-5  text-center ">
          <h3>
            <!--            Optimisez la gestion de vos excédents avec une solution simple et efficace.<br>-->
            <span class="text-success">Publiez</span> vos annonces, <span class="text-success">Gérez</span> les réservations,
            et <span class="text-success">Connectez-vous</span> rapidement avec vos clients grâce à <br><em class="text-success ">MonGP</em>
            <!--            notre plateforme dédiée à la gratuité partielle.-->
          </h3>
        </div>
        <div class="container">
            <div class="row w-75 m-auto p-5 background rounded ">
              <div class="form-floating col-md-4">
                <input type="date"  v-model="searchDepart" class="form-control border border-success p-5" id="floatingInputGroup1" placeholder="">
                <label for="floatingInputGroup1">Départ</label>
              </div>
              <div class="form-floating col-md-4">
                <input type="date" v-model="searchArrivee" class="form-control border border-success p-5" id="floatingInputGroup1" placeholder="">
                <label for="floatingInputGroup1">Arrivée</label>
              </div>
              <div class="col-md-4">
                <button class="btn btn-success border p-4 my-2" id="search">Trouve un GP</button>
              </div>
            </div>
        </div>
      </div>
    </div>
    <div class="album py-5 bg-body-tertiary" v-if="filteredAnnonces">
      <div class="container">
        <div class="container w-75  d-flex m-4 p-5 gap-5">
          <div class="rounded-circle shadow p-3 bg-light">
            <svg stroke="currentColor" class="text-success" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="3.1em" width="3.1em" xmlns="http://www.w3.org/2000/svg">
              <path d="M624 448H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h608c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM80.55 341.27c6.28 6.84 15.1 10.72 24.33 10.71l130.54-.18a65.62 65.62 0 0 0 29.64-7.12l290.96-147.65c26.74-13.57 50.71-32.94 67.02-58.31 18.31-28.48 20.3-49.09 13.07-63.65-7.21-14.57-24.74-25.27-58.25-27.45-29.85-1.94-59.54 5.92-86.28 19.48l-98.51 49.99-218.7-82.06a17.799 17.799 0 0 0-18-1.11L90.62 67.29c-10.67 5.41-13.25 19.65-5.17 28.53l156.22 98.1-103.21 52.38-72.35-36.47a17.804 17.804 0 0 0-16.07.02L9.91 230.22c-10.44 5.3-13.19 19.12-5.57 28.08l76.21 82.97z">
              </path>
            </svg>
          </div>
          <div class="">
            <h4 class="hover:underline mt-2">Liste des annonces</h4>
            <div class="h4 pb-2 width mb-4 border-bottom border-success"></div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="table-responsive">

          <table id="dataTableExample" class="table table-bordered">
            <thead>
            <tr>
              <th>GP</th>
              <th>Depart</th>
              <th>Arrivée</th>
              <th>Disponible(Kg)</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            <tr v-if="annonces" v-for="annonce in filteredAnnonces">
              <td>
                <router-link to="/profile/index" class="navbar-brand">
                  <img :src="annonce.user.storage+'/'+annonce.user.photo_profile" width="35px" alt="Photo Profile" class="rounded-circle mx-1"/>
                  {{annonce.user.name}}
                </router-link>
              </td>
              <td>{{annonce.date_depart}}</td>
              <td>{{annonce.date_arrivee}}</td>
              <td>{{annonce.kilos_disponibles}}</td>
              <td class="d-flex d-inline-block gap-2">
                <button class="btn btn-sm btn-danger">
                  <i data-feather="delete"></i>
                </button>
                <a class="btn btn-sm btn-success" href="#">
                  <i data-feather="edit"></i>
                </a>
              </td>
            </tr>
            <tr v-else>
                <p class="text-center">Aucun resultat trouvé</p>
            </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>


  </main>
  <div class="container">
    <FooterHome />
  </div>
</template>

<style scoped>

</style>