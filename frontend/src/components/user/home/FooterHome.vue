<script setup>
import logo from '@/assets/images/logo.png'
</script>

<template>

  <footer class="">
    <section class="d-flex justify-content-between align-items border-top bg-success py-0 text-light">
      <router-link class="text-light text-decoration-none" to="/">
        <img :src="logo" alt="Logo" width="60" height="60">
        MonGP
      </router-link>
      <p class="pt-4 px-3">&copy; 2024 Company, Inc. All rights reserved. Prod By <span class="text-muted">@root</span></p>
    </section>
  </footer>
</template>
<style scoped>

</style>