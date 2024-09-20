<script setup>
import axios from "@/axios.js";
import {ref} from "vue";
import Swal from "sweetalert2";
const order  = JSON.parse(localStorage.getItem('order'))
const orderId = order.order.id
const invoice_url = ref('')
const onUploads = async () => {
  const r = await axios.get(`/invoice/${orderId}`,{
    headers:{
      'Authorization':`Bearer ${localStorage.getItem('token')}`
    }
  })
   invoice_url.value = await r.data.invoice_url
  //console.log(invoice_url.invoice_url)
  await Swal.fire({
    title: 'success',
    text: 'Le lien de téléchargement est généré.',
    icon: 'success',
    confirmButtonText:'Ok',
  });
}
</script>

<template>
<div class="container w-50 m-5 m-auto">
  <div class="container border border-success rounded-4 shadow p-5 m-5">
    <h1 class="text-center">Merci pour votre achat !</h1>
    <p class="text-center" v-if="orderId">Votre paiement pour la commande <span class="text-success">#{{ orderId }}</span> a été reçu avec succès.</p>
    <p class="text-center">Vous pouvez générer votre facture en cliquant sur le bouton ci-dessous :</p>
    <p class="text-center"><button @click="onUploads" class="btn btn-sm btn-outline-success">Générer</button></p>
    <p class="text-center"><a :href="invoice_url" target="_blank" v-if="invoice_url">Lien de téléchargement...</a></p>
  </div>
</div>
</template>

