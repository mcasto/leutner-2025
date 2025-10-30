<template>
  <q-page>
    <div class="q-mx-md">
      <div class="flex justify-end q-mt-sm">
        <q-btn
          icon="mdi-arrow-left"
          color="primary"
          class="text-black"
          @click="store.router.go(-1)"
        ></q-btn>
      </div>

      <div class="text-h6">
        {{ store.article.title }}
      </div>
      <div class="text-subtitle1">By {{ store.article.byline }}</div>
      <div class="text-subtitle2">{{ store.article.date }}</div>
      <div v-if="store.article.url" class="text-overline text-blue">
        <a :href="store.article.url" target="_blank">{{
          store.article.urlLabel
        }}</a>
      </div>

      <q-separator class="q-my-md"></q-separator>

      <div v-html="store.article.contents" class="article-body"></div>
    </div>
  </q-page>
</template>

<script setup>
// store
import { useStore } from "stores/store";
const store = useStore();
</script>
