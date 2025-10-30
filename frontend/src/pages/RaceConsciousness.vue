<template>
  <q-page class="book-page">
    <div class="row q-mt-md q-mx-md">
      <div class="column col-12 col-md-6 justify-start items-center">
        <q-img
          src="/images/cover-with-spine-3d.png"
          class="cover-with-spine"
          fit="contain"
        ></q-img>

        <div class="book-info q-mt-md">
          <div class="text-h6 text-bold">
            The Critical American Conversation
          </div>

          <div class="large-text">
            It is a difficult, confusing, and even dangerous time to talk about
            race in the United States of America. But it is also a necessary
            time, because the divisive forces of racism, which underly most of
            America's ills, continue to fester. Carol Leutner knows those forces
            firsthand and up-front. She fought them, she analyzed them, she
            risked to ameliorate them, and ultimately succumbed to them and
            emigrated from the United States. This is the story of how Carol got
            educated on race in America.
          </div>

          <call-to-action
            class="q-mt-md"
            url="https://www.amazon.com/Race-Consciousness-Personal-Political-Journey-ebook/dp/B0CH42X8D3/ref=sr_1_1?dib=eyJ2IjoiMSJ9.8T9kiJ_SC2ON5Ezh_orjLA.3YxMQ0IlNbrYQSJ_2kieJbx62hM51uwYhizM78e-7oc&dib_tag=se&keywords=carol+leutner&qid=1719664184&sr=8-1"
            title="Race Consciousness: A Personal and Political Journey"
          ></call-to-action>
        </div>
      </div>

      <div class="col-12 col-md-6" :class="Screen.lt.md ? 'q-mt-md' : ''">
        <div :class="Screen.gt.sm ? 'text-h3' : 'text-h6'">
          What Others Are Saying
        </div>

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
