<template>
  <q-page>
    <q-toolbar class="bg-black text-white justify-between">
      <div :style="`height: ${toolbarHeight}; width: ${toolbarHeight}`">
        <q-img src="/images/globe.png" fit="contain"></q-img>
      </div>
      <div class="banner-text text-h4">
        Carol E. Leutner
      </div>
      <div>
        &nbsp;
      </div>
    </q-toolbar>

    <div class="row">
      <div class="col-12 col-sm-8 q-pa-md large-text">
        <p>
          Welcome to my website and thank you for your interest in
          <em>Race Consciousness, A Personal and Political Journey</em>. Talking
          about differences in skin color can be uncomfortable. The fact that
          you are visiting my website, however, tells me that you, as a reader,
          are willing to engage in this troublesome topic, no matter how
          difficult.
        </p>
        <p>
          I made a similar decision decades ago. Now I want to share the story
          of my life as I have lived it across racial, cultural, and gender
          divides both in the United States and abroad. I hope my journey and
          analysis inform and enlighten you. If they also help to expand the
          conversation on race, then I will have achieved my goal.
        </p>
        <p>
          In January 2025, I published my second book:
          <span class="text-italic">
            21st Century Paradigm Shift: What It Is and How to Thrive In It.
          </span>
          This short but impactful read takes a deep dive into why humanity now
          faces so many existential threats: the climate emergency, AI, wealth
          inequality, and nuclear war. Should we blame capitalism or human
          greed, and is there a way out? Explore with me how our outdated view
          of reality, based on 500-year-old science, is at fault and see where
          the new science of quantum physics might take us.
        </p>
        <p>
          Also, check out my
          <router-link to="/media/articles">articles</router-link> on culture,
          race, and economics on Medium or read them here on my site [under the
          Media tab in the navigation]. Thank you for your interest in my
          writing, and let me know your thoughts.
        </p>

        <p>&mdash; Carol E. Leutner</p>

        <q-separator></q-separator>

        <div class="row items-center">
          <div class="col-4">
            <q-img
              src="/images/winner-sticker.png"
              :width="Screen.gt.sm ? '132px' : '100px'"
            ></q-img>
          </div>
          <div class="col">
            <em>Race Consciousness: A Personal and Political Journey</em> has
            won the 2024 New Generation Indie Book Award in the category Memoir:
            History/Legacy. This award is given annually by the Independent Book
            Publishing Professionals Group, Inc.
          </div>
        </div>

        <call-to-action
          class="q-mt-md"
          title="<ul><li><em>Race Consciousness: A Personal and Political Journey</em></li><li><em>21st Century Paradigm Shift: What It Is and How to Thrive In It</em></li></ul>"
          url="https://www.amazon.com/stores/Carol-E.-Leutner/author/B0CGG9JM8N?ref=ap_rdr&isDramIntegrated=true&shoppingPortalEnabled=true"
        ></call-to-action>
      </div>

      <div class="col-12 col-sm-4 q-pa-md">
        <q-img src="/images/carol.jpg"></q-img>
      </div>
    </div>
  </q-page>
</template>

<script>
import { Screen } from "quasar";
import CallToAction from "components/CallToAction.vue";

export default {
  name: "IndexPage",
  components: { CallToAction },
  data: () => ({
    Screen,
  }),
  computed: {
    toolbarHeight() {
      return `${Screen.height * 0.065}px`;
    },
    fontSize() {
      if (Screen.width > 750) return "5rem";
      if (Screen.width > 625) return "4rem";
      if (Screen.width > 500) return "3rem";
      return "2rem";
    },
    globeWidth() {
      return Screen.width < 450 ? "4rem" : "8rem";
    },
    toolbarGlobe() {
      const h = Screen.height;
    },
  },

  mounted() {
    //
  },
};
</script>
