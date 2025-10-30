import callApi from "src/assets/call-api";
import { useStore } from "../store";

export default async () => {
  const store = useStore();

  const nav = await callApi({ path: "/get-navigation", method: "get" });

  nav.forEach((item) => {
    if (item.children && item.children.length > 0) {
      store.subVisible[item._id] = false;
    }
  });

  store.navigation = nav;
};
