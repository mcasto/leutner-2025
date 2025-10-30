<template>
  <div v-if="Screen.gt.sm">
    Site developed by
    <a href="https://castoware.com" class="text-black">CastoWare Development</a>
  </div>
  <div v-else class="q-my-xs">
    <div>Site by:</div>
    <q-img
      src="/storage/images/castoware-logo.png"
      width="2rem"
      fit="contain"
    ></q-img>
  </div>
</template>

<script setup>
// setup
import { Screen } from "quasar";

// store
import { useStore } from "stores/store";
const store = useStore();
</script>
