<template>
  <q-page>
    <div>
      <q-toolbar>
        <q-icon
          name="fa-solid fa-folder-tree"
          @click="showTree = true"
        ></q-icon>
        <q-toolbar-title> {{ node?.label || "All Media" }} </q-toolbar-title>
      </q-toolbar>
    </div>

    <q-drawer v-model="showTree" class="bg-primary">
      <q-tree
        :nodes="media"
        node-key="id"
        selected-color="secondary"
        v-model:selected="selected"
        @update:selected="mediaSelected"
        default-expand-all
        no-transition
        v-if="media"
      />
    </q-drawer>

    <div>
      <media-cards
        :cards="displayMedia"
        @view-media="(card) => emit('view-media', card)"
        @set-selected="(id) => (selected = id)"
      ></media-cards>
    </div>
  </q-page>
</template>

<script setup>
  import { useStore } from "stores/store";
  const store = useStore();

  const props = defineProps(["media", "displayMedia"]);
  const emit = defineEmits(["get-specific-media"]);

  import { ref } from "vue";
  import findSelectedNode from "assets/find-selected-node";

  import MediaCards from "components/MediaCards.vue";

  const showTree = ref(false);
  const selected = ref(null);
  const node = ref(null);

  const mediaSelected = () => {
    if (!selected.value) {
      emit("get-specific-media");
    }

    node.value = findSelectedNode(props.media, selected.value);
    emit("get-specific-media", node.value);

    showTree.value = false;
  };
</script>
