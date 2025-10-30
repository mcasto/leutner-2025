<template>
  <q-dialog v-model="model">
    <q-card style="width: 100%;">
      <div class="flex justify-end">
        <q-btn icon="close" round flat v-close-popup></q-btn>
      </div>
      <q-separator></q-separator>
      <q-card-section>
        <q-img :src="row.image"></q-img>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
const model = defineModel();
const props = defineProps(["row"]);
</script>
