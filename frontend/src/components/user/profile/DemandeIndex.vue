<script setup>
import {onMounted, ref} from "vue";
import axios from "@/axios.js";

const demandes = ref([])
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
            <span class="badge bg-primary mx-1">{{annonce.annonce.origin}}</span><span class="badge bg-primary">{{annonce.annonce.destination}}</span>
          </a></td>
          <td class="text-center" v-text="annonce.kilos_demandes"></td>
          <td class="text-center" v-text="annonce.prix_de_la_demande"></td>
          <td class="text-center" v-if="annonce.status === 'en_attente'"><router-link :to="`/payment/${annonce.id}`" class="btn btn-sm btn-danger rounded-4">{{ annonce.status }}</router-link></td>
          <td class="text-center" v-else><span class="badge bg-success">{{ annonce.status }}</span></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>

</style>