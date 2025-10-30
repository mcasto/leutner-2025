<template>
  <q-card square flat>
    <q-card-section>
      <!-- <div class="text-subtitle1">by {{ card.byline }}</div> -->
      <div class="text-subtitle2">
        {{ cardDate }}
      </div>
      <div class="text-caption" v-if="card.url">
        <a :href="card.url" target="_blank"
          >View Original On {{ card.folder }}</a
        >
      </div>
    </q-card-section>
    <q-card-section>
      <div v-html="article"></div>
    </q-card-section>
  </q-card>
</template>

<script>
  import { useStore } from "stores/store";
  import { mapState } from "pinia";
  import { format, parseISO } from "date-fns";

  export default {
    name: "DisplayArticles",
    props: ["card"],
    computed: {
      ...mapState(useStore, ["articles"]),
      article() {
        return this.articles[this.card._id];
      },
      cardDate() {
        return format(parseISO(this.card.date), "PP");
      },
    },
  };
</script>
