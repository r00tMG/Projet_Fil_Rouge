<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const properties = ref([]);
const searchPrice = ref('');
const searchSurface = ref('');
const searchRooms = ref('');
const searchTitle = ref('');

// Fonction pour charger les propriétés au montage
const loadProperties = async () => {
  const response = await axios.get('http://localhost:8001/api/property', {
    params: {
      price: searchPrice.value,
      surface: searchSurface.value,
      rooms: searchRooms.value,
      title: searchTitle.value,
    },
  });
  properties.value = response.data;
  console.log(properties.value)
};

// Charger les propriétés au montage du composant
onMounted(loadProperties);

// Fonction pour déclencher la recherche
const searchProperties = () => {
  loadProperties();
};
</script>

<template>
  <div class="property-search">
    <form @submit.prevent="searchProperties">
      <div class="row">
        <div class="col-md-3">
          <label for="price">Prix maximum</label>
          <input type="number" v-model="searchPrice" class="form-control" id="price" placeholder="Prix maximum" />
        </div>
        <div class="col-md-3">
          <label for="surface">Surface minimum</label>
          <input type="number" v-model="searchSurface" class="form-control" id="surface" placeholder="Surface minimum (m²)" />
        </div>
        <div class="col-md-3">
          <label for="rooms">Nombre de pièces minimum</label>
          <input type="number" v-model="searchRooms" class="form-control" id="rooms" placeholder="Nombre de pièces" />
        </div>
        <div class="col-md-3">
          <label for="title">Titre</label>
          <input type="text" v-model="searchTitle" class="form-control" id="title" placeholder="Titre" />
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Rechercher</button>
    </form>

    <div class="properties-list mt-5">
      <h3>Liste des propriétés</h3>
      <div v-if="properties.length">
        <ul class="list-group">
          <li class="list-group-item" v-for="property in properties" :key="property.id">
            <h5>{{ property.title }}</h5>
            <p>Prix: {{ property.price }} €</p>
            <p>Surface: {{ property.surface }} m²</p>
            <p>Pièces: {{ property.rooms }}</p>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>Aucune propriété trouvée.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Styles personnalisés si nécessaire */
</style>
