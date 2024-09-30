<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from "@/axios.js";
import logo from '@/assets/images/logo.png';
import Swal from "sweetalert2";
import Loader from "@/components/Loader.vue";
const name = ref('');
const permissions = ref([]);
const allPermissions = ref([]);
const errors = ref({});
const router = useRouter();
const token = localStorage.getItem('token');
const isLoading=ref(false)
const fetchPermissions = async () => {
  try {
    const response = await axios.get('/permissions', {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    allPermissions.value = response.data;
    //console.log(allPermissions.value)
  } catch (error) {
    console.error("Error fetching permissions:", error);
  }
};

const submitForm = async () => {
  try {
    const response = await axios.post('/roles', {
      name: name.value,
      permission: permissions.value,
    }, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });
    const role = await response.data
    console.log(role)
    // Rediriger vers la page des rôles si la création est réussie
    if (role.status === 201) {
      Swal.fire({
        title:'success',
        text:response.data.message,
        icon:'success',
        confirmButton: 'OK'

      })
      await router.push('/roles/index');
    }

  } catch (error) {
    if (error.response && error.response.status === 400) {
      errors.value = error.response.data.errors;
    } else {
      console.error("Error creating role:", error);
    }
  }
};

// Récupérer les permissions lors du montage du composant
fetchPermissions();
</script>

<template>
  <Loader  :isLoading="isLoading"/>
  <div class="container border border-success mt-2 rounded-4 shadow p-3">
    <p class="tulisan_login">Créer un role</p>
    <img :src="logo" alt="Logo">
    <form @submit.prevent="submitForm" >
      <div class="form-group mb-3">
        <label>name</label>
        <input type="text" v-model="name" name="name" class="form_login" placeholder="Name..">
        <p v-if="errors.name" class="text-danger">{{errors.name[0]}}</p>
      </div>
      <div class="mb-3">
        <label for="permission" class="form-label"><strong>Permissions:</strong></label>
        <div class="list-group">
          <label
              v-for="permission in allPermissions"
              :key="permission.id"
              class="list-group-item d-flex align-items-center">
            <input
                type="checkbox"
                v-model="permissions"
                :value="permission.id"
                class="form-check-input me-3"
            />
            {{ permission.name }}
          </label>
        </div>
      </div>

      <input type="submit" class="tombol_login" value="Create">
    </form>

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
  background: #3D8854;
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