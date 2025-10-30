<template>
  <q-splitter :model-value="25">
    <template #before>
      <q-page>
        <q-tree
          :nodes="media"
          node-key="id"
          selected-color="secondary"
          v-model:selected="selected"
          default-expand-all
          no-transition
          v-if="media"
        />
      </q-page>
    </template>

    <template #after>
      <q-page>
        <media-cards
          :cards="displayMedia"
          @view-media="(card) => $emit('view-media', card)"
          @set-selected="(id) => (selected = id)"
        ></media-cards>
      </q-page>
    </template>
  </q-splitter>
</template>

<script>
  import findSelectedNode from "assets/find-selected-node";
  import MediaCards from "components/MediaCards.vue";

  export default {
    name: "MediaWeb",
    props: ["displayMedia", "media"],
    components: { MediaCards },
    data: () => ({
      selected: null,
    }),
    watch: {
      selected() {
        const id = this.selected;

        if (!id) {
          this.$emit("get-specific-media");
          return;
        }

        const node = findSelectedNode(this.media, id);

        this.$emit("get-specific-media", node);
      },
    },
  };
</script>
