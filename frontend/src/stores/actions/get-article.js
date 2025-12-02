import callApi from "src/assets/call-api";
import { useStore } from "stores/store";
import MarkdownIt from "markdown-it";
const md = new MarkdownIt({ html: true });

export default (id) => {
  const store = useStore();
  return callApi({ path: `/get-article/${id}`, method: "get" }).then(
    (article) => {
      // article.contents = md.render(article.contents);
      // store.article = article;

      if (!article.contents) {
        // assume it's a link to PDF
        window.open(article.url);
        return "/media/articles";
      }
    }
  );
};
