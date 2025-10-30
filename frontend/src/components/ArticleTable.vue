<template>
  <div>
    <q-table
      :columns="columns"
      :rows="articles"
      flat
      class="page-background"
      dense
      bordered
      table-header-class="bg-primary"
      table-class="special-table"
      grid
      :pagination="{ rowsPerPage: 0 }"
      hide-bottom
    >
      <template #item="{row}">
        <div
          class="q-table__grid-item col-12 col-md-6 col-lg-3"
          @click="() => $router.push(`/view-article/${row._id}`)"
        >
          <div
            class="q-table__grid-item-card q-table__card cursor-pointer full-height"
          >
            <div class="q-table__grid-item-row">
              <div class="q-table__grid-item-title">Name</div>
              <div
                class="q-table__grid-item-value text-h6"
                v-html="row.title"
              ></div>
              <div class="q-table__grid-item-title">Byline</div>
              <div
                class="q-table__grid-item-value text-subtitle1"
                v-html="row.byline"
              ></div>
            </div>
            <div class="q-table__grid-item-row">
              <div class="q-table__grid-item-title">Date</div>
              <div class="q-table__grid-item-value text-subtitle2">
                {{ row.date }}
              </div>
            </div>
          </div>
        </div>
      </template>
    </q-table>
  </div>
</template>

<script setup>
// setup
import { ref } from "vue";

// store
import { useStore } from "stores/store";
const store = useStore();

// props
const props = defineProps(["category"]);

// data
const article = ref({ visible: false });
const articles = store.media.articles[props.category.id];

const columns = [
  {
    label: "Title",
    name: "title",
    field: (row) => row.title,
  },
  {
    label: "Byline",
    name: "byline",
    field: "byline",
  },
  {
    label: "Date",
    name: "date",
    field: "date",
  },
];
</script>
