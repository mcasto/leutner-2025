<template>
  <div class="row q-gutter-y-sm">
    <div
      class="col-12 col-md-6 q-px-sm"
      v-for="card in cards"
      :key="`media-card-${card._id}`"
    >
      <media-card
        :card="card"
        @view-media="(card) => $emit('view-media', card)"
        @set-selected="(id) => $emit('set-selected', id)"
      ></media-card>
    </div>
  </div>
</template>

<script setup>
  // props
  const props = defineProps(["cards"]);

  // components
  import MediaCard from "components/MediaCard.vue";
</script>
