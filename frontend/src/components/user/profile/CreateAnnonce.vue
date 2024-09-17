<script setup>
import {ref} from "vue";
import axios from "@/axios.js";
import {useRouter} from "vue-router";
import logo from '@/assets/logo.png';
const kilos_disponibles = ref('')
const date_depart = ref('')
const date_arrivee = ref('')
const origin = ref('')
const destination = ref('')
const description = ref('')
const prix_du_kilo = ref('')
const annonce = ref([])
const errors = ref({})
const router = useRouter()
//console.log(localStorage.getItem('token'))
const onSubmit = async () => {
  try {
    const response = await axios.post('/annonces',{
      //user_id:user_id.value,
      kilos_disponibles:kilos_disponibles.value,
      date_depart:date_depart.value,
      date_arrivee:date_arrivee.value,
      description:description.value,
      origin:origin.value,
      destination:destination.value,
      prix_du_kilo:prix_du_kilo.value
    } ,{
      headers: {
        'Content-Type':'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    annonce.value = await response.data
    if (response.data.status === 400) {
      errors.value = response.data.errors;
      alert(response.data.message);
    } else {
      await router.push('/annonces/index');
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
</script>

<template>
  <div class="container m-5 rounded-5 shadow border border-success ">
    <div class="p-5 w-100">
      <p class="tulisan_login">Publier une annonce</p>
        <img :src="logo" alt="Logo">
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="form-group col-md-6 mb-3">
            <label>Kilo(s) Disponible(s)</label>
            <input type="number" v-model="kilos_disponibles" name="kilos_disponibles" class="form_login " placeholder="Kilo(s) Disponible(s)...">
            <p v-if="errors.kilos_disponibles" class="text-danger">{{ errors.kilos_disponibles[0] }}</p>
          </div>
          <div class="form-group col-md-6 mb-3">
            <label>Prix du Kilo(DH)</label>
            <input type="number" v-model="prix_du_kilo" name="prix_du_kilo" class="form_login " placeholder="Prix du Kilo(DH)...">
            <p v-if="errors.prix_du_kilo" class="text-danger">{{ errors.prix_du_kilo[0] }}</p>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6 mb-3">
            <label>Date et Heure de départ</label>
            <input type="datetime-local" v-model="date_depart" name="date_depart" class="form_login" placeholder="Date départ...">
            <p v-if="errors.date_depart" class="text-danger">{{ errors.date_depart[0] }}</p>
          </div>
          <div class="form-group col-md-6 mb-3">
            <label>Date et Heure d'arrivée</label>
            <input type="datetime-local" v-model="date_arrivee"	name="date_arrivee" class="form_login" placeholder="Date d'arrivée...">
            <p v-if="errors.date_arrivee" class="text-danger">{{ errors.date_arrivee[0] }}</p>
          </div>
        </div>
        <div class="row">
        <div class="form-group col-md-6 mb-3">
          <label>Origine</label>
          <input type="text" v-model="origin"	name="origin" class="form_login" placeholder="Origine...">
          <p v-if="errors.origin" class="text-danger">{{ errors.origin[0] }}</p>
        </div>
        <div class="form-group col-md-6 mb-3">
          <label>Destination</label>
          <input type="text" v-model="destination"	name="destination" class="form_login" placeholder="Destination...">
          <p v-if="errors.destination" class="text-danger">{{ errors.destination[0] }}</p>
        </div>
        </div>
        <div class="form-group mb-3">
          <div class="form-group mb-3">
            <label>Description</label>
            <textarea type="text" v-model="description" name="description" class="form_login" placeholder="Description..."></textarea>
            <p v-if="errors.description" class="text-danger">{{errors.description[0]}}</p>
          </div>
        </div>

        <input type="submit" class="tombol_login" value="Publier">
      </form>

    </div>
  </div>

</template>


<style scoped>
body {
  font-family: sans-serif;
  background: brown;
}

h1 {
  text-align: center;
  font-weight: 300;
}

.tulisan_login {
  text-align: center;

  text-transform: uppercase;
}

.kotak_login {
  /*width: 350px;*/
  background: #f2f2f2;
  /*tengah*/
  margin: 50px auto;
  padding: 30px 20px;
  box-shadow: 0 0 21px;
}

label {
  font-size: 11pt;
}

.form_login {
  box-sizing: border-box;
  width: 100%;
  padding: 10px;
  font-size: 11pt;
  margin-bottom: 20px;
}

.tombol_login {
  background: green;
  color: white;
  font-size: 11pt;
  width: 100%;
  border: none;
  border-radius: 3px;
  padding: 10px 20px;
  cursor:pointer;
}

.link {
  color: #232323;
  text-decoration: none;
  font-size: 10pt;
}

img {

  border-radius: 50%;
  width: 150px;
  margin: 0 auto;
  display: block;
}
input[type="submit"]:hover {
  background-color: #45a049;

}
select {
  -webkit-appearance:none;
  -moz-appearance:none;
  -ms-appearance:none;
  appearance:none;
  outline:0;
  box-shadow:none;
  border:0!important;
  background: #5c6664;
  background-image: none;
  flex: 1;
  padding: 0 .5em;
  color:#fff;
  cursor:pointer;
  font-size: 1em;
  font-family: 'Open Sans', sans-serif;
}
select::-ms-expand {
  display: none;
}
.select {
  position: relative;
  display: flex;
  width: 20em;
  height: 3em;
  line-height: 3;
  background: #5c6664;
  overflow: hidden;
  border-radius: .25em;
}
.select::after {
  content: '\25BC';
  position: absolute;
  top: 0;
  right: 0;
  padding: 0 1em;
  background: #2b2e2e;
  cursor:pointer;
  pointer-events:none;
  transition:.25s all ease;
}
.select:hover::after {
  color: #23b499;
}
</style>