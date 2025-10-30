<template>
  <q-menu :anchor="anchorAlign" :self="selfAlign">
    <q-list dense separator class="bg-primary">
      <q-item
        v-for="child in children"
        :key="child.id"
        clickable
        :to="child.path"
        active-class="text-black text-underline"
      >
        <q-item-section>
          <q-item-label v-html="child.label"> </q-item-label>
        </q-item-section>
        <sub-menu
          :children="child.children"
          :offset="offset || 'left'"
          v-if="child.children?.length > 0"
        ></sub-menu>
      </q-item>
    </q-list>
  </q-menu>
</template>

<script setup>
  // setup
  import { computed } from "vue";

  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // props
  const props = defineProps(["children", "offset"]);

  // data
  const anchorAlign = computed(() => {
    if (props.offset == "left") {
      return "center start";
    }
    if (props.offset == "right") {
      return "center end";
    }
    return "bottom left";
  });

  const selfAlign = computed(() => {
    if (props.offset == "left") {
      return "top end";
    }
    if (props.offset == "right") {
      return "top start";
    }
    return "top left";
  });
</script>
