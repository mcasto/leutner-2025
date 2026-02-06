<template>
  <div>
    <a :href="url" target="_blank" class="call-to-action-link">
      <q-card class="page-background">
        <q-card-section>
          <div class="text-h6 text-black" v-html="title"></div>
        </q-card-section>
        <q-card-section class="flex justify-end">
          <q-img
            src="/storage/images/paradigm-audible.png"
            width="10vw"
            fit="contain"
            class="q-mr-md"
          ></q-img>
          <q-img
            src="/storage/images/available-at-amazon.svg"
            width="10vw"
            fit="contain"
          ></q-img>
        </q-card-section>
      </q-card>
    </a>
  </div>
</template>

<script setup>
// store
import { useStore } from "stores/store";
const store = useStore();

const props = defineProps(["url", "title"]);
</script>
