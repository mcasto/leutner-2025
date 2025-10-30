import { useStore } from "src/stores/store";

const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Home",
        component: () => import("pages/IndexPage.vue"),
      },
      {
        path: "books",
        name: "Books",
        children: [
          {
            path: "race-consciousness",
            name: "Race Consciousness",
            component: () => import("src/pages/RaceConsciousness.vue"),
            beforeEnter: (to, from, next) => {
              const store = useStore();
              store.getReviews("race-consciousness");
              next();
            },
          },
          {
            path: "paradigm-shift",
            name: "Paradigm Shift",
            component: () => import("src/pages/ParadigmShift.vue"),
            beforeEnter: (to, from, next) => {
              const store = useStore();
              store.getReviews("paradigm-shift");
              next();
            },
          },
        ],
      },
      {
        path: "media",
        name: "Media",
        component: () => import("pages/MediaPage.vue"),
        children: [
          {
            path: "",
            name: "no-media-page",
            component: () => import("pages/ErrorNotFound.vue"),
          },
          {
            path: "press-releases",
            name: "Press Releases",
            component: () => import("pages/media/PressReleases.vue"),
            beforeEnter: (to, from, next) => {
              useStore().getPressReleases();
              next();
            },
          },
          // {
          //   path: "forthcoming-publications",
          //   name: "Forthcoming Publications",
          //   component: () => import("pages/media/ForthcomingPublications.vue"),
          //   beforeEnter: (to, from, next) => {
          //     useStore().getForthcomingPublications();
          //     next();
          //   },
          // },
          {
            path: "articles",
            name: "Articles",
            component: () => import("pages/media/ArticlesPage.vue"),
            beforeEnter: async (to, from, next) => {
              await useStore().getArticles();
              next();
            },
          },
          {
            path: "lectures",
            name: "Events",
            component: () => import("pages/media/LecturesPage.vue"),
            beforeEnter: async (to, from, next) => {
              await useStore().getLectures();
              next();
            },
          },
          {
            path: "learning-tools",
            name: "Learning Tools",
            component: () => import("pages/media/LearningTools.vue"),
          },
        ],
      },

      {
        path: "lecture/:id",
        name: "Lecture",
        component: () => import("pages/media/LecturePage.vue"),
        beforeEnter: async (to, from, next) => {
          const store = useStore();
          await store.getLectures();
          store.lecture = store.lectures
            .filter(({ _id }) => _id == to.params.id)
            .shift();
          next();
        },
      },
      {
        path: "schedule-speaking-engagement",
        name: "Schedule Speaking Engagement",
        component: () => import("pages/ScheduleEngagement.vue"),
        beforeEnter: async (to, from, next) => {
          const store = useStore();
          next();
        },
      },
      {
        path: "view-article/:id",
        name: "View Article",
        component: () => import("pages/media/ArticlePage.vue"),
        beforeEnter: async (to, from, next) => {
          await useStore().getArticle(to.params.id);
          next();
        },
      },

      {
        path: "about",
        name: "About",
        component: () => import("pages/AboutPage.vue"),
      },
      {
        path: "contact",
        name: "Contact",
        component: () => import("pages/ContactPage.vue"),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
