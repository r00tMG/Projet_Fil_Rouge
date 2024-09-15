<template>
  <div class="w-50 m-5 border rounded-4 p-5 border-success m-auto">
    <h4 class="bg-success p-3 text-center text-white rounded-4">Valider votre achat</h4>
    <form @submit.prevent="handleSubmit">

      <div id="card-element" class="m-3 border rounded-2 border-success p-3">
      </div>
      <div class="form-group text-center ">
          <button class="btn btn-success px-5 rounded-5" type="submit" :disabled="processing">Payer</button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from '@/axios.js';
import {useRoute, useRouter} from "vue-router";
//4242 4242 4242 4242
export default {
  setup() {
    const stripe = ref(null);
    const elements = ref(null);
    const cardElement = ref(null);
    const processing = ref(false);
    const payment_intent_id = ref('')
    const total = ref('')
        const email = ref('')
    const route = useRoute()
    const router = useRouter()
    const initializeStripe = () => {
      stripe.value = Stripe('pk_test_51PxoVZBrhUmW23Q4V3daDnZ6G2miRhZwMFYsf4kzvHmID1tMknp5TRECvdixuCu79g7CdhE3eqgorNdAIW65fg8400nnwAbyy0');
      //console.log(stripe.value)
      elements.value = stripe.value.elements();
//console.log(elements.value)
      cardElement.value = elements.value.create('card');
      cardElement.value.mount('#card-element');
    };

    onMounted( () => {
      initializeStripe();
    });
//console.log(JSON.parse(localStorage.getItem('maDemande')).prix_de_la_demande)
    const handleSubmit = async () => {
      processing.value = true;

      const { data } = await axios.post('/payment-intent', {
        amount: JSON.parse(localStorage.getItem('maDemande')).prix_de_la_demande,
        headers:{
          'Authorization':`Bearer ${localStorage.getItem('token')}`
        }
      });

      const clientSecret = data.clientSecret;
      console.log(clientSecret)
      const result = await stripe.value.confirmCardPayment(clientSecret, {
        payment_method: {
          card: cardElement.value,
        },
      });

      if (result.error) {
        console.error(result.error.message);
        processing.value = false;
      } else {
        if (result.paymentIntent.status === 'succeeded') {
          console.log(JSON.parse(localStorage.getItem('maDemande')).client.email)
          const r =  await axios.post('create/orders',{
              payment_intent_id:clientSecret,
              total:JSON.parse(localStorage.getItem('maDemande')).prix_de_la_demande,
              demande_id:route.params.id,
            email:JSON.parse(localStorage.getItem('maDemande')).client.email
          },{
            headers:{
              'Authorization':`Bearer ${localStorage.getItem('token')}`
            }
          })
          const order = await r.data
          console.log(order)
          console.log(JSON.parse(localStorage.getItem('maDemande')).client.email)
          if (order)
          {
            localStorage.setItem('order',JSON.stringify(order))
            localStorage.removeItem('maDemande')
            await router.push('/payment/success')
          }
        }
        processing.value = false;
      }
    };
    //console.log(route.params.id)

    return {
      handleSubmit,
      processing,
    };
  },
};
</script>
