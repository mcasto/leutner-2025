import callApi from "src/assets/call-api";
import { useStore } from "stores/store";

export default () => {
  const store = useStore();
  callApi({ path: "/get-press-releases", method: "get" }).then((response) => {
    store.media.pressReleases = response;
  });
};
