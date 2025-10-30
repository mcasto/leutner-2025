import { useStore } from "../store";
import callApi from "src/assets/call-api";

export default async (slug) => {
  const store = useStore();

  store.reviews = await callApi({
    path: `/get-reviews/${slug}`,
    method: "get",
  });
};
