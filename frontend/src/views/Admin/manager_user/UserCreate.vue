<script>
import { onMounted, ref } from 'vue'
import axios from "@/axios.js";
import { useRouter } from 'vue-router'
import logo from '@/assets/logo.png';
export default {
  name: 'UserCreate',
  setup(){
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const password_confirmation = ref('')
    const errors = ref({})
    const photo_profile = ref(null)
    const selectRoles = ref([])
    const getRoles = ref([])
    const data = ref([])
    const router = useRouter()
    const token = localStorage.getItem('token')
    const onProfile = (e) => {
      photo_profile.value = e.target.files[0]
      console.log('Selected file:', photo_profile.value);
    }
    onMounted(async () => {
      const r = await axios.get('/roles',{
        headers: {
          'Accept':'application/json',
          'Authorization':`Bearer ${token}`
        }
      })
      getRoles.value = await r.data
      //console.log(getRoles.value)

    })

    const onRegister = async () => {
      const formData = new FormData()
      formData.append('name', name.value)
      formData.append('email', email.value)
      formData.append('password', password.value)
      formData.append('password_confirmation', password_confirmation.value)
      //formData.append('roles', selectRoles.value)
      selectRoles.value.forEach(role => {
        formData.append('roles', role);
      });

      if(photo_profile.value){
        formData.append('photo_profile', photo_profile.value)
        console.log(photo_profile.value)
      }
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }
      try{

        const response = await axios.post('/users',formData,{
          headers: {
            //'Content-Type': 'multipart/form-data',
            'Authorization':`Bearer ${token}`
          },
        })

        data.value = response.data
        console.log(data.value)
        if (response.data.status === 400) {
          errors.value = response.data.errors;
          alert(response.data.message);
        } else {
          await router.push('/users/index');
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
    return{
      name,
      email,
      password,
      password_confirmation,
      onRegister,
      errors,
      onProfile,
      data,
      selectRoles,
      getRoles,
      logo
    }
  }
}
</script>

<template>
<div class="container mt-5  shadow rounded-5 border border-success">
  <div class="p-5 w-100">
    <p class="tulisan_login">Créer un utilisateur</p>
    <img :src="logo" alt="Logo">
    <form @submit.prevent="onRegister" enctype="multipart/form-data">
      <div class="row">
        <div class="form-group col-md-6 mb-3">
          <label>name</label>
          <input type="text" v-model="name" name="name" class="form_login" placeholder="Name..">
          <div v-if="errors.name" class="text-danger">{{ errors.name[0] }}</div>
        </div>

        <div class="form-group col-md-6 mb-3">
          <label>Email</label>
          <input type="text" v-model="email" name="email" class="form_login" placeholder="Email..">
          <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6 mb-3">
          <label>Password</label>
          <input type="password" v-model="password"	name="password" class="form_login" placeholder="Password ..">
          <div v-if="errors.password" class="text-danger">{{ errors.password[0] }}</div>
        </div>
        <div class="form-group col-md-6 mb-3">
          <label>Password Confirmation</label>
          <input type="password" v-model="password_confirmation"	name="password_confirmation" class="form_login" placeholder="Password ..">
          <div v-if="errors.password_confirmation" class="text-danger">{{ errors.password_confirmation[0] }}</div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6" >
          <label>Photo Profile</label>
          <input type="file"  @change="onProfile"	name="photo_profile" class="form-control form_login" >
          <div v-if="errors.photo_profile" class="text-danger">{{ errors.photo_profile[0] }}</div>
        </div>
        <div class="form-group col-md-6 mb-3">
          <label>Role(s): </label>
          <select class="form-select" name="roles" v-model="selectRoles" id="roles" multiple>
            <option v-for="getRole in getRoles.roles" :key="getRole.id" :value="getRole.name" >{{getRole.name}}</option>
          </select>
          <p v-if="errors.roles" class="text-danger">{{errors.roles[0]}}</p>
        </div>
      </div>
      <input type="submit" class="tombol_login" value="Register">
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
  width: 350px;
  background: #f2f2f2;
  /*tengah*/
  margin: 80px auto;
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