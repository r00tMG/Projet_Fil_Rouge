<script setup>
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
const roles = ref([])
  onMounted(async () => {
    const r = await axios.get('/roles',{
      headers:{
        'Accept':'application/json',
        'Authorization':`Bearer ${localStorage.getItem('token')}`
      }
    })
     roles.value = await r.data
    //console.log(roles.value.roles.length)

  })
const onDelete = async (id) =>{
  //console.log(id)
  if (confirm('Êtes-vous sûr de vouloir supprimer ce role ?')) {

    const r = await axios.delete(`http://backend.test/api/roles/${id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    const data = r.data
    alert(data.message)
  }
}

</script>

<template>
  <div class="mt-5">
    <div class="d-flex justify-content-between my-2">
      <h4>Liste des roles</h4>
      <router-link to="/roles" class="btn btn-sm btn-success ">Create</router-link>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered  table-striped">
        <thead class="table-success">
        <tr class="negrita">
          <th colspan="2">Name</th>
          <th colspan="6">Permissions</th>
          <th colspan="2">Actions</th>
        </tr>
        </thead>
        <tbody >
        <tr v-if="roles" v-for="role in roles.roles" :key="role.id">
          <td colspan="2"  v-text="role.name"></td>
          <td colspan="3" v-if="role.permission.length>0">
            <span class="badge bg-primary p-1"  v-for="permission in role.permission">{{permission.name}}</span>
          </td>
          <td colspan="3"  v-else>
            <span class="badge bg-primary p-1">Aucune Permission</span>
          </td>
          <td colspan="4" >
            <button class="btn btn-sm btn-danger me-1" @click="onDelete(role.id)">Delete</button>
            <router-link :to="`/roles/${role.id}/edit`" class="btn btn-sm btn-success">Edit</router-link>
          </td>
        </tr>
        <tr v-else>
          Loading...
        </tr>
        </tbody>
      </table>
    </div>
  </div>

</template>

