<template>
  <q-page class="q-px-md">
    <div class="row">
      <div class="col-12 col-md-8">
        <q-toolbar>
          <q-toolbar-title>
            <div v-html="store.lecture.title"></div>
          </q-toolbar-title>
        </q-toolbar>
        <div
          v-html="store.lecture.video"
          class="q-mt-md flex justify-center"
        ></div>
      </div>

      <div class="col-12 col-md-4 q-mt-md">
        <lecture-carousel></lecture-carousel>
      </div>
    </div>

    <div>
      <div v-html="store.lecture.meta.event"></div>
      <div class="text-caption">Photos by {{ store.lecture.meta.credit }}</div>
    </div>
  </q-page>
</template>

<script setup>
  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // components
  import LectureCarousel from "components/LectureCarousel.vue";
</script>
