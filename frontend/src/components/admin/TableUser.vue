<script setup>
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import Swal from "sweetalert2";
const users = ref([])
  onMounted(async () => {
    const r = await axios.get('/users',{
      headers:{
        'Accept':'application/json',
        'Authorization':`Bearer ${localStorage.getItem('token')}`
      }
    })
     users.value = await r.data
    console.log(users.value)
  })
  const onDelete = async (id) => {
    //console.log(id)
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
      const r = await axios.delete(`/users/${id}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      const data = r.data
      Swal.fire({
        title:'success',
        text: data.message,
        icon:'success',
        confirmButton: 'Ok'

      })
    }
  }

</script>

<template>
  <div class="mt-5">
    <div class="d-flex justify-content-between my-2">
      <h4>Liste des users</h4>
      <router-link to="/users" class="btn btn-sm btn-success">Create</router-link>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered  table-striped">
        <thead class="table-success">
        <tr>
          <th colspan="2">Name</th>
          <th colspan="4">Email</th>
          <th colspan="3">Photo Profile</th>
          <th colspan="4">Role</th>
          <th colspan="4">Actions</th>
        </tr>
        </thead>
        <tbody >
        <tr v-if="users" v-for="user in users.users" :key="user.id">
          <td colspan="2"  v-text="user.name"></td>
          <td colspan="4"  v-text="user.email"></td>
          <td colspan="4" >
            <img :src="users.storage + '/' + user.photo_profile" class="rounded-circle" width="40" height="40">
          </td>
          <td colspan="3" v-if="user.roles.length>0" v-for="role in user.roles">
            <span class="badge bg-primary p-1">{{role.name}}</span>
          </td>
          <td colspan="3" v-else>
            <span class="badge bg-primary p-1">Aucun role</span>
          </td>
          <td colspan="4" >
            <button class="btn btn-sm btn-danger me-1" @click="onDelete(user.id)">Delete</button>
            <router-link :to="`/users/${user.id}/edit`" class="btn btn-sm btn-success">Edit</router-link>
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

