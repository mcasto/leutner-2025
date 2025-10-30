<template>
  <component :is="componentName" :card="card"></component>
</template>

<script>
  import { startCase } from "lodash";
  import DisplayArticles from "components/DisplayArticles.vue";
  import DisplayPhotos from "components/DisplayPhotos.vue";
  import DisplayPressReleases from "src/components/DisplayPressReleases.vue";
  import DisplayVideos from "components/DisplayVideos.vue";

  export default {
    name: "ViewMedia",
    components: {
      DisplayArticles,
      DisplayPhotos,
      DisplayPressReleases,
      DisplayVideos,
    },
    props: ["card"],
    computed: {
      componentName() {
        return `Display${startCase(this.card.display_type).replace(/\s/g, "")}`;
      },
    },
  };
</script>
