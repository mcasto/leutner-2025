<template>
  <q-layout view="lHh Lpr lFf">
    <q-header
      class="flex justify-between text-black q-px-md items-center shadow-1"
    >
      <div class="text-h6">{{ store.router.currentRoute.value.name }}</div>

      <navigation-management></navigation-management>
    </q-header>

    <nav-drawer></nav-drawer>

    <q-page-container class="page-background watermark">
      <router-view />
    </q-page-container>
    <q-footer>
      <q-toolbar class="flex justify-between">
        <q-toolbar-title class="text-black text-caption" shrink>
          <casto-ware></casto-ware>
        </q-toolbar-title>
        <div class="text-black text-caption">
          ©2023 Carol E. Leutner
        </div>
        <a
          href="https://www.amazon.com/Race-Consciousness-Personal-Political-Journey-ebook/dp/B0CH42X8D3/ref=sr_1_1?dib=eyJ2IjoiMSJ9.8T9kiJ_SC2ON5Ezh_orjLA.3YxMQ0IlNbrYQSJ_2kieJbx62hM51uwYhizM78e-7oc&dib_tag=se&keywords=carol+leutner&qid=1719664184&sr=8-1"
          target="_blank"
        >
          <q-img
            src="/images/available-at-amazon.svg"
            width="5rem"
            fit="contain"
          ></q-img>
        </a>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<script setup>
// setup'
import { useMeta } from "quasar";

// store
import { useStore } from "stores/store";
const store = useStore();

// components
import CastoWare from "components/CastoWare.vue";
import NavDrawer from "components/NavDrawer.vue";
import NavigationManagement from "components/NavigationManagement.vue";
import { onMounted } from "vue";

// meta
useMeta(() => {
  let title = store.router.currentRoute.value.name;
  if (title == "View Article") {
    title += ` (${store.article.title})`;
  }
  if (title == "Lecture") {
    title += ` (${store.lecture.title})`;
  }

  return {
    title,
    titleTemplate: (title) => `Carol Leutner - ${title}`,
  };
});

onMounted(() => {
  //
});
</script>
