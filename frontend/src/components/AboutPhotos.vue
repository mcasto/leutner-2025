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
  </div>
</template>

<script>
import { Screen } from "quasar";

export default {
  name: "AboutPhotos",
  data: () => ({
    sideImages: [
      {
        src: "/images/family.jpg",
        caption:
          "<div>Carol and Family</div><div>An interview with Carol's eldest daughter, Hilary Anderson (1973-1997) can be viewed <a href='https://www.theinsite.org/been_there/hilary_phys_dis_alias.html' target='_blank' class='text-white'>here.</a></div>",
      },
    ],
    Screen,
  }),
};
</script>
