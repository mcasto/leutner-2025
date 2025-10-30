<template>
  <q-page class="q-pa-md">
    <!-- mc-todo: add schedule speaking engagement -->

    <!-- <q-toolbar class="shadow-1">
      <q-toolbar-title>
        Schedule Speaking Engagement with Carol
      </q-toolbar-title>
      <q-btn
        icon="event"
        round
        color="primary"
        class="text-black"
        to="/schedule-speaking-engagement"
      ></q-btn>
    </q-toolbar> -->

    <q-table
      :columns="columns"
      :rows="store.lectures"
      dense
      class="page-background q-mt-md"
      table-header-class="bg-primary"
      table-class="special-table"
      @row-click="(e, { _id }) => $router.push(`/lecture/${_id}`)"
      v-if="store.lectures"
      grid
    >
      <template #item="{row}">
        <div class="q-table__grid-item col-12 col-md-6 col-lg-3">
          <div class="q-table__grid-item-card q-table__card cursor-pointer">
            <div class="q-table__grid-item-row">
              <div class="q-table__grid-item-title">Name</div>
              <div
                class="q-table__grid-item-value text-h6"
                v-html="row.title"
              ></div>
            </div>
            <div class="q-table__grid-item-row">
              <div class="q-table__grid-item-title">Date</div>
              <div class="q-table__grid-item-value text-subtitle1">
                {{ row.date }}
              </div>
            </div>
            <q-separator></q-separator>
            <div class="row">
              <div class="col text-center">
                <q-btn
                  :disabled="!!!row.video"
                  label="Video"
                  color="primary"
                  class="text-black"
                  @click="
                    store.lecture = {
                      ...row,
                      dialogContents: 'video',
                      visible: true,
                    }
                  "
                ></q-btn>
              </div>
              <div class="col text-center">
                <q-btn
                  :disabled="row?.photos?.length == 0"
                  label="Photos"
                  color="primary"
                  class="text-black"
                  @click="openPhotos(row)"
                ></q-btn>
              </div>
            </div>
          </div>
        </div>
      </template>
    </q-table>
    <lecture-dialog v-model="store.lecture"></lecture-dialog>
  </q-page>
</template>

<script setup>
// store
import { useStore } from "stores/store";
const store = useStore();

// components
import LectureDialog from "src/components/LectureDialog.vue";

// data
const columns = [
  {
    label: "Name",
    name: "title",
  },
  {
    label: "Date",
    name: "date",
    field: "date",
  },
];

const openPhotos = (row) => {
  const payload = {
    ...row,
    dialogContents: "photos",
    visible: true,
  };

  store.lecture = payload;
};
</script>
