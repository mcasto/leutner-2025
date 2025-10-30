<template>
  <q-page>
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="column flex flex-center justify-space-around">
          <q-img
            src="/images/carol-with-medal.jpg"
            :width="Screen.gt.sm ? '35vw' : ''"
            :height="Screen.lt.md ? '60vh' : ''"
            :position="Screen.lt.md ? 'top' : ''"
            class="q-mr-1 q-mb-5"
            :fit="Screen.gt.sm ? 'contain' : 'cover'"
          ></q-img>
        </div>

        <div v-if="Screen.gt.sm">
          <about-photos></about-photos>
        </div>
      </div>

      <div class="col q-pa-md">
        <div class="text-h6">
          About The Author
        </div>

        <div class="q-my-md">
          Carol E. Leutner's career spans over forty years as an advocate,
          lawyer, poltical activist, and international civil servant.
        </div>

        <div class="q-my-md">
          Her advocacy work has been primarily in rural economic development,
          and she has been instrumental in realigning relationships between
          private and public sector institutions to funnel resources to
          disadvantaged populations. Throughout her career, she promoted
          greatear dialogue and understanding across racial and cultural
          divides.
        </div>
        <div class="q-my-md">
          Her professional development work began on the Navajo reservation
          where a food and nutritional program she led resulted in new and
          historic federal financing for tribal agricultural enterprises. This
          work continued in northern New Mexico helping to improve water
          management policies for subsistence farmers.
        </div>
        <div class="q-my-md">
          After receiving her MBA and JD degrees from the University of New
          Mexico, she successfully tried labor and race discrimination cases.
          She and her African American husband, a human relations and
          communications consultant and father of their two daughters, built an
          extensive multiracial and multicultural network and political base in
          New Mexico, which culminated in their leadership of Jesse Jackson's
          1984 presidental campaign in the state.
        </div>
        <div class="q-my-md">
          Following the diagnosis of her older daughter's life-threatening
          disease and the family's relocation to the East Coast, Ms. Leutner
          worked in the nation's capitol to secure financing for a new national
          rural transportation lending program and later rebuilt a nonprofit
          organization in the community of Capitol Hill.
        </div>
        <div class="q-my-md">
          After settling her daughters in college and completing a Masters
          degree in international law from Georgetown Law School, Ms. Leutner
          began a new career at age 50, working first as a consultant on law and
          development issues for the United Nation's three Rome-based food and
          agricultural organizations. She later took a contracts officer's post
          in procurement until her retirement in 2006 and subsequently worked as
          a procurement consultant for numerous U.N. agencies in Rome, Beijing,
          Washington D.C., and Southeast Asia.
        </div>
        <div class="q-my-md">
          She has written and published in the field of rural development and
          economics, including an award-winning article on the history of
          economic thought. She currently resides in Cuenca, Ecuador.
        </div>
        <div class="q-my-md">
          <em>Race Consciousness</em> is her first book.
        </div>
        <div class="q-my-md">
          You can contact her <router-link to="/contact">here</router-link>.
        </div>

        <q-img src="images/books-with-medal.jpg" class="q-my-sm"></q-img>
      </div>
    </div>

    <div v-if="Screen.lt.md">
      <about-photos></about-photos>
    </div>

    <div class="q-mt-sm row">
      <div
        class="col-12 col-md-6 text-center q-pa-sm"
        v-for="image in bottomImages"
        :key="image.src"
      >
        <q-img :src="image.src" fit="cover" height="500px">
          <div
            style="position: absolute; bottom: 0;"
            class="full-width text-center"
            v-html="image.caption"
          ></div>
        </q-img>
      </div>
    </div>
  </q-page>
</template>

<script>
import { Screen } from "quasar";
import AboutPhotos from "components/AboutPhotos.vue";

export default {
  name: "AboutPage",
  components: { AboutPhotos },
  data: () => ({
    bottomImages: [
      {
        src: "/images/cuenca-historic.jpeg",
        caption: "Historic Center, Cuenca",
      },
      {
        src: "/images/inca-terraces.jpg",
        caption: "Inca Terraces, Pumapungo, Cuenca",
      },
    ],
    Screen,
  }),
};
</script>
