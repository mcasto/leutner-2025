<template>
  <q-page class="book-page">
    <div class="row q-mt-md q-mx-md">
      <div class="column col-12 col-md-6 justify-start items-center">
        <q-img
          src="/images/paradigm-cover.png"
          class="cover-with-spine"
          fit="contain"
          width="80%"
          style="max-height: 700px;"
        ></q-img>
      </div>
      <div class="col-12 col-md-6">
        <div class="book-info q-mt-md">
          <div class="text-h6 text-bold text-uppercase">
            A New Framework For Our Time
          </div>

          <div class="large-text">
            <p>
              Carol E. Leutner's
              <span class="text-italic"
                >21st Century Paradigm Shift: What It Is and How to Thrive In
                It</span
              >
              is about economics, philosophy and so much more.
              <span class="text-italic">Paradigm Shift</span>
              traces how 300+ years of Newtonian-based science have framed our
              current perception of reality and proposes that the new field of
              quantum physics contains the building blocks for a revised version
              of reality suitable to the historical challenges we face.
            </p>
            <p>
              Given that we live in a time of conflicting and competing versions
              of reality when large swaths of the body politic cannot agree on
              the Truth, Carol's book could not be more timely.
            </p>
            <p>
              An award-winning academic version of this work was published in
              the Review of Social Economy in 1982, Paradigm Shift is available
              as an e-book on Amazon.
            </p>
          </div>

          <call-to-action
            class="q-mt-md"
            url="https://www.amazon.com/21st-Century-Paradigm-Shift-Thrive-ebook/dp/B0DTZQ5X8B/"
            title="21st Century Pardigm Shift: What It Is and How to Thrive In It"
          ></call-to-action>
        </div>
      </div>

      <div class="col-10 offset-1" :class="Screen.lt.md ? 'q-mt-md' : ''">
        <div :class="Screen.gt.sm ? 'text-h3' : 'text-h6'">
          What Others Are Saying
        </div>

        <div
          v-for="(review, idx) in reviews"
          :key="`review-${idx}`"
          class="q-my-lg"
        >
          <div v-html="review"></div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { Screen } from "quasar";
import { useStore } from "stores/store";
import { mapState } from "pinia";
import CallToAction from "components/CallToAction.vue";

export default {
  name: "BookPage",
  components: { CallToAction },
  data: () => ({
    Screen,
  }),
  computed: {
    ...mapState(useStore, ["reviews"]),
  },
};
</script>
