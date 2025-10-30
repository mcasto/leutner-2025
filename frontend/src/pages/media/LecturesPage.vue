<template>
  <q-page class="q-pa-md">
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
          <div
            class="q-table__grid-item-card q-table__card cursor-pointer column"
            style="height: 100%;"
          >
            <div class="row no-wrap items-start">
              <!-- Main content area -->
              <div class="col">
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
              </div>

              <!-- Image on the right -->
              <div v-if="row.image && Screen.gt.sm" class="col-auto q-pl-sm">
                <q-img
                  :src="row.image"
                  class="q-table__grid-item-image"
                  style="width: 80px; height: 80px;"
                  fit="cover"
                  @click="openImage(row)"
                />
              </div>
            </div>

            <!-- Spacer to push buttons to bottom -->
            <div class="col"></div>

            <!-- Full width separator and buttons -->
            <div>
              <q-separator class="full-width"></q-separator>
              <div class="row full-width">
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
        </div>
      </template>
    </q-table>
    <lecture-dialog v-model="store.lecture"></lecture-dialog>
    <event-image
      v-model="eventImage.visible"
      :row="eventImage.row"
    ></event-image>
  </q-page>
</template>

<script setup>
// store
import { useStore } from "stores/store";
import { Screen } from "quasar";
import EventImage from "src/components/EventImage.vue";

const store = useStore();

// components
import LectureDialog from "src/components/LectureDialog.vue";
import { ref } from "vue";

const eventImage = ref({
  visible: false,
  row: null,
});

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

const openImage = (row) => {
  eventImage.value = {
    visible: true,
    row,
  };
};
</script>
