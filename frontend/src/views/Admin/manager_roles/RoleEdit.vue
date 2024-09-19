<script setup>
import {useRoute, useRouter} from "vue-router";
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import logo from '@/assets/images/logo.png';
import Swal from "sweetalert2";

const route = useRoute()
const name = ref('')
const permission = ref([])
const selectPermissions = ref([])
const permissions = ref([])
const role = ref([])
const router = useRouter()
const errors = ref({})
const data = ref([])

let token = localStorage.getItem('token')

onMounted(async () => {
  const r = await axios.get(`/roles/${route.params.id}`,{
    headers: {
      'Accept':'application/json',
      'Authorization':`Bearer ${token}`
    }
  })
  role.value = await r.data
  //console.log(role.value.role.permission)
  name.value = role.value.role.name
  selectPermissions.value = role.value.role.permission
  //console.log(name.value, selectPermissions.value)
})
// Collection des roles
onMounted(async () => {
  const r = await axios.get('/permissions',{
    headers: {
      'Accept':'application/json',
      'Authorization':`Bearer ${token}`
    }
  })
  permissions.value = await r.data
  //console.log(permissions.value)
})

const onSubmit = async () => {
  //console.log("Selected Permissions:", selectPermissions.value);
  try{
    const response = await axios.put(`/roles/${route.params.id}`,{
      name:name.value,
      permission:selectPermissions.value
    },{
      headers: {
        'Content-Type':'application/json',
        'Authorization':`Bearer ${token}`
      },

    })
    //console.log(name,permission)
    data.value = await response.data
    //console.log(data.value)
    if (response.data.status === 400) {
      errors.value = response.data.errors;
      //alert(response.data.message);
      Swal.fire({
        title: 'success',
        text: response.data.message,
        icon: 'success',
        confirmButton:'Ok'
      })
    } else {
      await router.push('/roles/index');
      Swal.fire({
        title: 'success',
        text: response.data.message,
        icon: 'success',
        confirmButton:'Ok'
      })
    }
  } catch (error) {
    if (error.response && error.response.status === 400) {
      errors.value = error.response.data;
      //console.error("Validation errors:", errors.value.message);
      //alert(errors.value.message)
      Swal.fire({
        title: 'success',
        text: errors.value.message,
        icon: 'success',
        confirmButton:'Ok'
      })
    } else {
      console.error("Error: La requête a échoué", error.response);
    }
  }
}



</script>

<template>
<div class="container border border-success mt-2 rounded-4 shadow ">
  <div class="p-3 w-100">
    <p class="tulisan_login">Modifier un role</p>
    <img :src="logo" alt="Logo">
    <form @submit.prevent="onSubmit" >
      <div class="form-group mb-3">
        <label>name</label>
        <input type="text" v-model="name" name="name" class="form_login" placeholder="Name..">
        <p v-if="errors.name" class="text-danger">{{errors.name[0]}}</p>
      </div>
      <div class="form-group mb-3">
        <label>Permission(s): </label>
        <select class="form-select" name="permission" v-model="selectPermissions" id="permission" multiple>
          <option v-for="permission in permissions" :key="permission.id" :value="permission.name">
            {{ permission.name }}
          </option>
        </select>
                <p v-if="errors.permissions" class="text-danger">{{errors.permissions[0]}}</p>
      </div>
      <input type="submit" class="tombol_login" value="Update">
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