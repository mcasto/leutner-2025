<template>
  <div class="q-pa-md">
    <div v-html="card.contents"></div>
  </div>
</template>

<script setup>
  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // props
  const props = defineProps(["card"]);
</script>
