<template>
  <nav-mobile v-if="Screen.lt.md"></nav-mobile>
  <nav-web v-else></nav-web>
</template>

<script setup>
  // setup
  import { Screen } from "quasar";

  // components
  import NavMobile from "components/NavMobile.vue";
  import NavWeb from "components/NavWeb.vue";
</script>
