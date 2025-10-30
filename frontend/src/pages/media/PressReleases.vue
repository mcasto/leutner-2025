<template>
  <q-page class="q-pa-md">
    <div>
      <div
        class="q-py-md"
        v-for="release in store.media.pressReleases"
        :key="`press-release-${release.id}`"
      >
        <q-card>
          <q-toolbar class="bg-primary">
            <q-toolbar-title>
              Press Release: {{ format(parseISO(release.release_date), "PP") }}
            </q-toolbar-title>
            <a :href="release.original_link" target="_blank" class="text-black">
              <q-btn icon="open_in_new" label="View Original"></q-btn>
            </a>
          </q-toolbar>
          <q-card-section>
            <div v-html="release.contents"></div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
// setup
import { format, parseISO } from "date-fns";

// store
import { useStore } from "stores/store";
const store = useStore();
</script>
