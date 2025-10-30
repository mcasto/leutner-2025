<template>
  <q-dialog v-model="config.visible" full-width>
    <q-card class="flex flex-center">
      <q-toolbar class="flex justify-end">
        <q-btn
          icon="close"
          round
          color="primary"
          class="text-black"
          @click="config.visible = false"
        ></q-btn>
      </q-toolbar>
      <q-card-section v-if="config.dialogContents == 'video'">
        <div v-html="config.video"></div>
      </q-card-section>
      <q-card-section v-if="config.dialogContents == 'photos'">
        <lecture-carousel></lecture-carousel>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
// store
import { useStore } from "src/stores/store";
const store = useStore();

// components
import LectureCarousel from "./LectureCarousel.vue";

// data
const config = defineModel();
</script>
