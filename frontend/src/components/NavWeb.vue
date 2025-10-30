<template>
  <q-tabs v-model="tab" dense>
    <template v-for="tab in store.navigation" :key="`nav-tab-${tab.id}`" exact>
      <q-route-tab
        :to="tab.path"
        :label="tab.label"
        v-if="!tab.children || tab.children.length == 0"
        exact
      ></q-route-tab>
      <q-tab v-else :label="tab.label">
        <sub-menu :children="tab.children"></sub-menu>
      </q-tab>
    </template>
  </q-tabs>
</template>

<script setup>
  // setup
  import { ref } from "vue";
  import items from "assets/navigation.json";

  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // components
  import SubMenu from "components/SubMenu.vue";

  // data
  const tab = ref(items[0].id);
</script>
