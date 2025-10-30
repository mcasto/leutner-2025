<template>
  <div>
    <q-card square flat>
      <q-card-section>
        <div class="text-subtitle2">
          {{ cardDate }}
        </div>
      </q-card-section>
    </q-card>

    <div v-html="embed"></div>
  </div>
</template>

<script>
  import { format, parseISO } from "date-fns";
  import { Screen } from "quasar";

  export default {
    name: "DisplayVideos",
    props: ["card"],
    computed: {
      cardDate() {
        return format(parseISO(this.card.date), "PP");
      },
      embed() {
        if (Screen.width > 600) return this.card.embed;

        const originalWidth = parseInt(
          this.card.embed.match(/width="([0-9]+)"/)[1]
        );
        const originalHeight = parseInt(
          this.card.embed.match(/height="([0-9]+)"/)[1]
        );

        const newWidth = parseInt(Screen.width - 50);
        const newHeight = parseInt((originalHeight / originalWidth) * newWidth);

        const embed = this.card.embed
          .replace(`width="${originalWidth}"`, `width="${newWidth}"`)
          .replace(`height="${originalHeight}"`, `height="${newHeight}"`);

        return embed;
      },
    },
  };
</script>
