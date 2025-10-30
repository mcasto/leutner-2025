<template>
  <div>
    <q-btn
      icon="menu"
      size="sm"
      label="Menu"
      @click="store.navDrawer = true"
      flat
    ></q-btn>
  </div>
</template>

<script setup>
  // setup
  import { ref } from "vue";

  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // data
  const visible = ref(false);
</script>
