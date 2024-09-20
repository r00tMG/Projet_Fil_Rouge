<script setup xmlns="http://www.w3.org/1999/html">

import FooterHome from "@/components/user/home/FooterHome.vue";
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import {useRoute} from "vue-router";
import SectionHomeContent from "@/components/user/home/SectionHomeContent.vue";
import Swal from "sweetalert2";
const annonce = ref([])
const errors = ref({})
const route = useRoute()
const annonce_id = ref('')
const user_id = ref('')
const kilos_demandes = ref('')
const panier = ref('')
const data = JSON.parse(localStorage.getItem('data'))
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
    const demande = await response.data.demande
    //console.log(demande)
    if (response.data.status === 400) {
      errors.value = response.data.errors;
      console.log(response.data.message);
      await Swal.fire({
        title:'error',
        text:response.data.message,
        icon:'error',
        confirmButton:'Ok'
      })
    } else {
      localStorage.setItem('maDemande',JSON.stringify(demande))
      console.log(response.data.message);
      await Swal.fire({
        title:'success',
        text:'Votre réservation a été bien enregistrée',
        icon:'success',
        confirmButton:'Ok'
      })
    }

    const panierElement = document.querySelector('#panier');
    let content = document.createElement('div');
    if (panierElement) {
      console.log(content)
      content.setAttribute('class', 'container mt-5 w-80');
      const maDemande = JSON.parse(localStorage.getItem('maDemande'))
      content.innerHTML = `
      <div class="card" style="background-color:darkgreen;">
        <div class="card-body">
          <div class="d-flex justify-content-between">
              <p class="card-title text-light">
                  <svg stroke="currentColor" class="" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                      <path d="M624 448H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h608c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM80.55 341.27c6.28 6.84 15.1 10.72 24.33 10.71l130.54-.18a65.62 65.62 0 0 0 29.64-7.12l290.96-147.65c26.74-13.57 50.71-32.94 67.02-58.31 18.31-28.48 20.3-49.09 13.07-63.65-7.21-14.57-24.74-25.27-58.25-27.45-29.85-1.94-59.54 5.92-86.28 19.48l-98.51 49.99-218.7-82.06a17.799 17.799 0 0 0-18-1.11L90.62 67.29c-10.67 5.41-13.25 19.65-5.17 28.53l156.22 98.1-103.21 52.38-72.35-36.47a17.804 17.804 0 0 0-16.07.02L9.91 230.22c-10.44 5.3-13.19 19.12-5.57 28.08l76.21 82.97z">
                      </path>
                  </svg>
                  Aller le ${new Date(maDemande.annonce.date_depart).toLocaleString('fr-FR',{day:'numeric',month:'long',year:'numeric'})} à ${new Date(maDemande.annonce.date_depart).getHours().toString()}h${new Date(maDemande.annonce.date_depart).getMinutes().toString()}min
              </p>
              <button class="circle" id="trash">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
              </button>
          </div>
          <p class="card-title text-center text-light">${maDemande.annonce.origin}
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right fs-1" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
          </svg>
          ${maDemande.annonce.destination}</p>
          <h6 class="card-subtitle mb-2 text-body-secondary">
          <span class="underline text-light">Poids: ${maDemande.kilos_demandes} Kg</span>
          </h6>
          <h6 class="card-subtitle mb-2 text-body-secondary">
          <span class="underline text-light">Prix: ${maDemande.prix_de_la_demande} DH</span>
          </h6>
        </div>
      </div>
      <div class="container text-center mt-5">
      <a href="/payment/${maDemande.id}" class="btn rounded-5 px-5" style="background-color:darkgreen;color: white"  >Continuer</a>
</div>
    `;
      panierElement.append(content);
    } else {
      console.error("L'élément #panier n'a pas été trouvé.");
    }
    const trash = document.querySelector('#trash')
    //console.log(trash)

    trash.addEventListener('click',(e)=>{
      e.preventDefault()
      if (confirm('Êtes-vous sûre?'))
      {
        console.log(document.querySelector('#panier'))
        content.remove()
        localStorage.removeItem('maDemande')
      }
    })
  } catch (error) {
    if (error.response && error.response.status === 400) {
      errors.value = error.response.data.errors;
      console.error("Validation errors:", errors.value);
      await Swal.fire({
        title:'error',
        text:errors.value,
        icon:'error',
        confirmButton:'Ok'
      })
    } else {
      console.error("Error: La requête a échoué", error);
      await Swal.fire({
        title:'error',
        text:error,
        icon:'error',
        confirmButton:'Ok'
      })
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
})
</script>

<template>
  <main>

    <div class="album row m-4">
      <div class="col-md-8">
        <div class="container  p-5 w-100 m-auto rounded-5 shadow ">
          <h4 class="w-50 text-center text-light rounded-5 m-auto my-5 bg-success p-3">Acheter mes poids par Kg</h4>
          <div class="row">
            <div class="col" v-if="annonce">
              <div class="card rounded-5 shadow-sm">
                <div class="card-body">
                  <form class="" @submit.prevent="onDemander" >
                    <div class="col-md-6 form-group" hidden="hidden">
                      <input type="number" class="form-control text-success rounded-5" v-model="annonce_id" placeholder="">
                    </div>
                    <div class="row mb-4">
                      <p>Mon trajet</p>
                      <div class="col-md-6 form-group">
                        <input type="text" class="form-control text-success rounded-5" readonly :value="annonce.origin" placeholder="">
                      </div>
                      <div class="col-md-6 form-group">
                        <input type="text" class="form-control text-success rounded-5" readonly :value="annonce.destination" placeholder="">
                      </div>
                    </div>
                    <div class="row mb-4">
                      <p>Mes dates</p>
                      <div class="col-md-6 form-group">
                        <input type="text" class="form-control text-success rounded-5" readonly :value="annonce.date_depart" placeholder="">
                      </div>
                      <div class="col-md-6 form-group">
                        <input type="text" class="form-control text-success rounded-5" readonly :value="annonce.date_arrivee" placeholder="">
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-md-6">
                        <label class="">Nombre de Kg</label>
                        <input type="number" class="form-control text-success rounded-5" min="0" :max="annonce.kilos_disponibles" v-model="kilos_demandes">
                        <p v-if="errors.kilos_demandes" class="text-danger">{{ errors.kilos_demandes[0] }}</p>
                        <p v-if="kilos_demandes">Vous allez payer <span class="text-success">{{kilos_demandes*annonce.prix_du_kilo}}</span> DHS</p>
                        <p v-else>0 DH</p>
                      </div>
                      <div class="col-md-3">
                        <label class="">Poids Disponible(Kg)</label>
                        <input class="form-control text-success rounded-5" readonly :value="annonce.kilos_disponibles" placeholder="">
                      </div>
                      <div class="col-md-3" hidden="hidden">
                        <label class=""></label>
                        <input type="number" class="form-control rounded-5" v-model="user_id" >
                      </div>
                    </div>
                    <div class="row">
                      <div class="d-flex flex-direction form-group mb-3">
                        <button type="submit" @click="" class="btn btn-success rounded-5">Réserver</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 bg-success " id="panier" style="height: 100vh">
        <h3 class="text-light p-3 mt-4">Mon panier</h3>
      </div>
    </div>

  </main>
  <div class="container">
    <FooterHome />
  </div>
</template>
