<template>
  <q-card>
    <q-toolbar class="flex justify-between q-mt-sm">
      <div style="max-width: 85%;">
        <div class="text-h6 ellipsis">
          {{ card.label }}
          <q-tooltip>
            {{ card.label }}
          </q-tooltip>
        </div>
        <div class="text-subtitle1" v-if="card.content_type == 'article'">
          by {{ card.byline }}
        </div>
        <div class="text-subtitle2" v-if="card.content_type == 'photo gallery'">
          Photos by {{ card.credit }}
        </div>
      </div>

      <q-icon
        class="cursor-pointer"
        name="visibility"
        size="sm"
        @click="viewMedia"
      ></q-icon>
    </q-toolbar>
    <q-separator
      v-if="
        card.content_type == 'article' || card.content_type == 'photo gallery'
      "
    ></q-separator>
    <q-card-section class="q-pt-none">
      <div class="text-subtitle2" v-if="card.date">
        {{ cardDate }}
      </div>
      <div class="text-caption">
        {{ contentType }}
      </div>
      <div v-if="card.url" class="text-caption">
        <a :href="card.url" target="_blank">
          View Original on {{ card.folder }}
        </a>
      </div>
    </q-card-section>
    <q-card-section
      v-if="
        card.content_type != 'article' &&
        card.content_type != 'photo gallery' &&
        card.content_type != 'video'
      "
    >
      <q-img
        v-if="card.thumbnail"
        :src="card.thumbnail"
        :fit="card.gallery_id ? 'contain' : 'cover'"
        :style="card.gallery_id ? 'max-height: 50vh' : ''"
      ></q-img>
    </q-card-section>
  </q-card>
</template>

<script setup>
  // setup
  import { computed } from "vue";
  import { startCase } from "lodash";
  import { format, parseISO } from "date-fns";

  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // emits
  const emit = defineEmits(["view-media", "set-selected"]);

  // props
  const props = defineProps(["card"]);

  // variables
  const contentType = computed(() => {
    return startCase(props.card.content_type);
  });

  const cardDate = computed(() => {
    return format(parseISO(props.card.date), "PP");
  });

  // methods
  const viewMedia = () => {
    if (props.card.display_type != "galleries") {
      emit("view-media", props.card);
      return;
    }

    const store = useStore();

    const media = store.media
      .filter(({ table }) => table == "galleries")
      .shift();

    const gallery = media.children
      .filter(({ gallery_id }) => gallery_id == props.card.id)
      .shift();

    emit("set-selected", gallery.id);
  };
</script>
