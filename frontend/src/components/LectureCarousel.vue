<template>
  <div>
    <div class="row justify-center">
      <q-btn-toggle
        glossy
        color="secondary"
        class="text-black"
        v-model="slide"
        :options="store.lecture.photos"
      />
    </div>

    <q-carousel
      v-model="slide"
      transition-prev="slide-right"
      transition-next="slide-left"
      animated
      control-color="secondary"
      class="rounded-borders bg-transparent full-height"
    >
      <template v-for="photo in store.lecture.photos" :key="photo.value">
        <q-carousel-slide
          :name="photo.value"
          class="column no-wrap flex-center"
        >
          <q-img :src="photo.src" fit="cover" style="min-width: 35vw;"></q-img>
        </q-carousel-slide>
      </template>
    </q-carousel>
  </div>
</template>

<script setup>
// setup
import { ref } from "vue";

// store
import { useStore } from "stores/store";
const store = useStore();

// data
const slide = ref(store.lecture.photos[0].value);
</script>
