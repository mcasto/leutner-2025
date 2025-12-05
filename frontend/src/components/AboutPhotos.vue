<template>
  <div class="q-mt-sm column flex flex-center q-gutter-y-md">
    <q-img
      v-for="image in sideImages"
      :key="image.src"
      :src="image.src"
      :width="Screen.gt.sm ? '35vw' : ''"
    >
      <div
        style="position: absolute; bottom: 0;"
        class="full-width text-center"
        v-html="image.caption"
      ></div>
    </q-img>

    <div
      class="flex items-center text-blue cursor-pointer"
      @click="videoDialogModel = true"
    >
      <q-icon name="mdi-video" class="q-mr-sm" size="sm"></q-icon>
      <div class="text-underline" style="font-size: 1rem;">
        A sister-to-sister tribute by Caroline Anderson on Disability Awareness
        Day 2025
      </div>
    </div>
    <caroline-video-dialog v-model="videoDialogModel"></caroline-video-dialog>
  </div>
</template>

<script>
import { Screen } from "quasar";
import CarolineVideoDialog from "src/components/CarolineVideoDialog.vue";

export default {
  name: "AboutPhotos",
  components: {
    CarolineVideoDialog,
  },
  data: () => ({
    videoDialogModel: false,

    sideImages: [
      {
        src: "/storage/images/family.jpg",
        caption:
          "<div>Carol and Family</div><div>An interview with Carol's eldest daughter, Hilary Anderson (1973-1997) can be viewed <a href='https://www.theinsite.org/been_there/hilary_phys_dis_alias.html' target='_blank' class='text-white'>here.</a></div>",
      },
    ],
    Screen,
  }),
};
</script>
