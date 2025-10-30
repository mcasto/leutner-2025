<template>
  <q-drawer v-model="store.navDrawer" class="bg-primary">
    <q-list dense separator>
      <template
        v-for="item in store.navigation"
        :key="`nav-list-item-${item.id}`"
      >
        <q-item
          clickable
          :to="item.path"
          active-class="text-black text-bold"
          exact
          v-if="!item.children || item.children.length == 0"
        >
          <q-item-section>
            <q-item-label>
              {{ item.label }}
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          v-else
          clickable
          @click="store.subVisible[item._id] = !store.subVisible[item._id]"
        >
          <q-item-section>
            <q-item-label> {{ item.label }} </q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="chevron_right"></q-icon>
          </q-item-section>
        </q-item>
        <q-item v-if="store.subVisible[item._id]">
          <q-list dense separator class="bg-primary">
            <q-item
              v-for="child in item.children"
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
        </q-item>
      </template>
    </q-list>
  </q-drawer>
</template>

<script setup>
  // store
  import { useStore } from "stores/store";
  const store = useStore();

  // components
  import SubMenu from "components/SubMenu.vue";
</script>
