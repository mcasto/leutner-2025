<template>
  <q-page>
    <q-tabs v-model="tab">
      <q-tab
        v-for="category in store.media.articleCategories"
        :key="`article-category-${category.id}`"
        :name="`${category.id}`"
        :label="category.label"
      >
      </q-tab>
    </q-tabs>
    <q-tab-panels v-model="tab">
      <q-tab-panel
        v-for="category in store.media.articleCategories"
        :key="`article-tab-panel-${category.id}`"
        :name="`${category.id}`"
        class="page-background"
      >
        <article-table :category="category"></article-table>
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>

<script setup>
  // setup
  import { ref } from "vue";

  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // components
  import ArticleTable from "src/components/ArticleTable.vue";

  // data
  const tab = ref("1");
</script>
