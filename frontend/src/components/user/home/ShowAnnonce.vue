<script setup xmlns="http://www.w3.org/1999/html">

import FooterHome from "@/components/user/home/FooterHome.vue";
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import {useRoute} from "vue-router";
import SectionHomeContent from "@/components/user/home/SectionHomeContent.vue";
  const annonce = ref([])
const errors = ref({})
  const route = useRoute()
const annonce_id = ref('')
const user_id = ref('')
const kilos_demandes = ref('')
const data = JSON.parse(localStorage.getItem('data'))
console.log(data.user.id)
const onDemander = async () => {
    try {
      const response = await axios.post('/demandes',{
        annonce_id:route.params.id,
        user_id:data.user.id,
        kilos_demandes:kilos_demandes.value
      },{
        headers:{
          'Content-Type':'application/json',
          'Authorization':`Bearer ${localStorage.getItem('token')}`
        }
      })
      const demande = await response.data
      console.log(demande)
      if (response.data.status === 400) {
        errors.value = response.data.errors;
        alert(response.data.message);
      } else {
        //await router.push('/login');
        alert(response.data.message);
      }
    } catch (error) {
      if (error.response && error.response.status === 400) {
        errors.value = error.response.data.errors;
        console.error("Validation errors:", errors.value);
      } else {
        console.error("Error: La requête a échoué", error);
      }
    }
}

onMounted(async () => {
  const r = await axios.get(`/annonces/${route.params.id}`,{
    headers:{
      'Accept':'application/json',
      'Authorization':`Bearer ${localStorage.getItem('token')}`
    }
  })
  annonce.value = await r.data.annonce
  console.log(annonce.value)
})
</script>

<template>
  <main>

    <div class="album p-5">
      <div class="container w-75">
        <h4 class="w-50 text-center text-light rounded-5 m-auto my-5 bg-success p-3">Acheter mes poids par Kg</h4>
        <div class="row">
          <div class="col" v-if="annonce">
            <div class="card shadow-sm">
              <div class="card-body">
                <form class="" @submit.prevent="onDemander" >
                  <div class="col-md-6 form-group" hidden="hidden">
                    <input type="number" class="form-control rounded-5" v-model="annonce_id" placeholder="">
                  </div>
                  <div class="row mb-4">
                    <p>Mon trajet</p>
                    <div class="col-md-6 form-group">
                      <input type="text" class="form-control rounded-5" :value="annonce.origin" placeholder="">
                    </div>
                    <div class="col-md-6 form-group">
                      <input type="text" class="form-control rounded-5" :value="annonce.destination" placeholder="">
                    </div>
                  </div>
                  <div class="row mb-4">
                    <p>Mes dates</p>
                    <div class="col-md-6 form-group">
                      <input type="text" class="form-control rounded-5" :value="annonce.date_depart" placeholder="">
                    </div>
                    <div class="col-md-6 form-group">
                      <input type="text" class="form-control rounded-5" :value="annonce.date_arrivee" placeholder="">
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-md-3">
                      <label class="">Poids Disponible(Kg)</label>
                      <input class="form-control rounded-5" :value="annonce.kilos_disponibles" placeholder="">
                    </div>
                    <div class="col-md-3">
                      <label class="">Je saisis le nombre de kilo</label>
                      <input type="number" class="form-control rounded-5" v-model="kilos_demandes">
                      <p v-if="errors.kilos_demandes" class="text-danger">{{ errors.kilos_demandes[0] }}</p>

                    </div>
                    <div class="col-md-3" hidden="hidden">
                      <label class=""></label>
                      <input type="number" class="form-control rounded-5" v-model="user_id" >
                    </div>
                  </div>
                  <div class="row">
                    <div class="d-flex flex-direction form-group mb-3">
                      <button type="submit" class="btn btn-success rounded-5">Demander</button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
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