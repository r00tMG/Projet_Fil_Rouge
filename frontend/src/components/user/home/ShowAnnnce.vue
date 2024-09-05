<script setup>

import FooterHome from "@/components/user/home/FooterHome.vue";
import {onMounted, ref} from "vue";
import axios from "@/axios.js";
import {useRoute} from "vue-router";
import SectionHomeContent from "@/components/user/home/SectionHomeContent.vue";
  const annonce = ref([])
  const route = useRoute()
onMounted(async () => {
  const r = await axios.get(`/annonces/${route.params.id}`,{
    headers:{
      'Accept':'application/json',
      'Authorization':`Bearer ${localStorage.getItem('token')}`
    }
  })
  annonce.value = await r.data.annonce
  //console.log(annonce.value)
})
</script>

<template>
  <main>
    <SectionHomeContent />
    <div class="album py-5 bg-body-tertiary">
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
          <div class="col" v-if="annonce">
            <div class="card shadow-sm">
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
              <div class="card-body">
                <p class="card-text" v-text="annonce.description"></p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                      <router-link :to="`/annonces/${annonce.id}`" class="nav-link">View</router-link>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                  </div>
                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
  <div class="container">
    <FooterHome />
  </div>
</template>

<style scoped>

</style>