<script setup>
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import Loader from "@/components/Loader.vue";

const demandes = ref([])
const isLoading = ref(false)

onMounted(async ()=>{
  const r = await axios.get('/demandes', {
    headers: {
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
  })
  demandes.value = await r.data
  console.log(demandes.value)
})
</script>

<template>
  <Loader  :isLoading="isLoading"/>

  <div class="mt-5">
    <div class="d-flex justify-content-between my-2">
      <h4 class="text-success">Mes Réservations</h4>
    </div>
    <div class=" table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-success">
        <tr>
          <th class="text-center px-2">GP</th>
          <th class="text-center px-2">Annonce</th>
          <th class="text-center px-2">Réservation(Kg)</th>
          <th class="text-center px-2">Prix(MAD)</th>
          <th class="text-center px-2">Status</th>
        </tr>
        </thead>
        <tbody >
        <tr v-if="demandes" v-for="annonce in demandes">
          <td class="text-center" v-text="annonce.client.name"></td>
          <td class="text-center"><a>
            <span class="badge bg-primary mx-1">{{annonce.annonce.origin}}</span>
            <span class="px-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
              </svg>
            </span>
            <span class="badge bg-primary">{{annonce.annonce.destination}}</span>
          </a></td>
          <td class="text-center" v-text="annonce.kilos_demandes"></td>
          <td class="text-center" v-text="annonce.prix_de_la_demande"></td>
          <td class="text-center" v-if="annonce.status === 'en_attente'"><router-link :to="`/payment/${annonce.id}`" class="badge bg-danger">{{ annonce.status }}</router-link></td>
          <td class="text-center" v-else><span class="badge bg-success">{{ annonce.status }}</span></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>

</style>