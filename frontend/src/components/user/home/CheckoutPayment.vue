<template>
  <div>
    <button @click="checkout">Payer</button>
  </div>
</template>

<script setup>
import {useRoute} from "vue-router";
import axios from "@/axios.js";

const route = useRoute()
console.log(route.params.id)
const checkout = async () => {
  try {
    const response = await axios.post('/create-checkout-session', {
      demande_id: route.params.id,
      headers:{
        'Authorization':`Bearer ${localStorage.getItem('token')}`
      }
    });

    const stripe = Stripe('pk_test_51PxoVZBrhUmW23Q4V3daDnZ6G2miRhZwMFYsf4kzvHmID1tMknp5TRECvdixuCu79g7CdhE3eqgorNdAIW65fg8400nnwAbyy0');
    stripe.redirectToCheckout({ sessionId: response.data.id });
  } catch (error) {
    console.error('Erreur lors de la création de la session Stripe', error);
  }
};
</script>
