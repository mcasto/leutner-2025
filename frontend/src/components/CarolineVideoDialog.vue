<template>
  <q-dialog v-model="model">
    <q-card>
      <div class="flex justify-end">
        <q-btn
          icon="close"
          flat
          round
          dense
          class="close-btn"
          @click="model = false"
        />
      </div>
      <q-card-section class="q-pa-md">
        <video controls class="full-width" style="max-height: 80vh;">
          <source
            src="/storage/videos/caroline-interview.mp4"
            type="video/mp4"
          />
          Your browser does not support the video tag.
        </video>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
const model = defineModel();
</script>
