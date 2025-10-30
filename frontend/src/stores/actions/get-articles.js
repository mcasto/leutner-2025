import callApi from "src/assets/call-api";
import { useStore } from "../store";
import { uniqBy } from "lodash";

export default async () => {
  const store = useStore();

  callApi({ path: "/get-articles", method: "get" }).then((articles) => {
    const categories = articles.map(
      ({ category_id, category_label, category_order }) => {
        return {
          id: category_id,
          label: category_label,
          order: category_order,
        };
      }
    );

    store.media.articleCategories = uniqBy(categories, "label").sort(
      (a, b) => a.order - b.order
    );

    const articleList = {};
    categories.forEach((cat) => {
      if (!articleList[cat.id]) {
        articleList[cat.id] = [];
      }

      articleList[cat.id] = articles.filter(
        ({ category_id }) => category_id == cat.id
      );
    });

    store.media.articles = articleList;
  });
};
