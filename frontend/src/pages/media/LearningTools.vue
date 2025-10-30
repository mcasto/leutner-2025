<template>
  <q-page class="q-pa-md">
    <div class="text-subtitle1 text-bold">
      Helpful Questions About Race Consciousness for Book Clubs and Discussion
      Groups
    </div>
    <div>
      Do you want to engage people with questions about this book? You can
      download a PDF of recommended questions for discussion.
    </div>

    <div class="q-my-md text-right">
      <q-btn
        class="bg-primary"
        label="Download PDF"
        href="/storage/downloadables/discussion-questions.pdf"
        target="_blank"
      ></q-btn>
    </div>

    <!-- mc-todo: video--use race consciousness for teaching & training -->
    <!-- mc-todo: race consciousness workbook -->
  </q-page>
</template>

<script setup>
// store
import { useStore } from "stores/store";
const store = useStore();
</script>
