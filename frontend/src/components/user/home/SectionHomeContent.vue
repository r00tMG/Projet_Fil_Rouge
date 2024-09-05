<script setup>
import {ref} from "vue";

const props = defineProps({
  searchDepart: String,
  searchArrivee: String,
  searchWeight: String,
});
const emit = defineEmits([
  'update:searchDepart',
  'update:searchArrivee',
  'update:searchWeight'
]);

const updateSearchDepart = (event) => {
  emit('update:searchDepart', event.target.value);
}

const updateSearchArrivee = (event) => {
  emit('update:searchArrivee', event.target.value);
}

const updateSearchWeight = (event) => {
  emit('update:searchWeight', event.target.value);
}


</script>

<template>



</template>

<style scoped>

</style>