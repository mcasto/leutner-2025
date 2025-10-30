import callApi from "src/assets/call-api";
import { useStore } from "stores/store";

export default () => {
  const store = useStore();
  return callApi({ path: "/get-lectures", method: "get" }).then((lectures) => {
    store.lectures = lectures;
  });
};
