<script>
import Dropdown from '@/components/Dropdown.vue'
import logo from '@/assets/logo.png'
export default {
  name: 'Navbar',
  components: { Dropdown },
  setup(){
    const token = localStorage.getItem('token')
    return {
      token,
      logo
    }
  }
}
</script>

<template>
  <div class="container  m-auto">
    <nav class="navbar navbar-expand-lg bg-body-light " data-bs-theme="dark">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand text-dark" :class="{ active: $route.path === `/` }" >
          <img :src="logo" alt="Logo" width="80px"><span class="text-success">MonGP</span></router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link to="/"  class="nav-link text-dark" :class="{ active: $route.path === `/` }">Home</router-link>
            </li>


          </ul>
          <Dropdown/>
        </div>
      </div>
    </nav>
  </div>

</template>

<style scoped>

</style>