import { defineStore } from "pinia";
import wretch from "wretch";

import getArticle from "./actions/get-article";
import getArticles from "./actions/get-articles";
import getLectures from "./actions/get-lectures";
import getNavigation from "./actions/get-navigation";
import getPressReleases from "./actions/get-press-releases";
import getReviews from "./actions/get-reviews";
import sendContact from "./actions/send-contact";

export const useStore = defineStore("store", {
  state: () => ({
    article: null,
    articles: null,
    api: wretch("/api"),
    displayMedia: null,
    lecture: { visible: false },
    lectures: null,
    media: { articles: [], articleCategories: [], pressReleases: [] },
    navDrawer: false,
    navigation: null,
    reviews: null,
    subVisible: {},
  }),
  actions: {
    getArticle,
    getArticles,
    getLectures,
    getNavigation,
    getPressReleases,
    getReviews,
    sendContact,
  },
  persist: {
    key: "leutner-site",
  },
});
