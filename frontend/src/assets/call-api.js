import { useStore } from "src/stores/store";
import handleResponse from "./handle-response";
import { Notify } from "quasar";
import wretch from "wretch";

export default ({ path, method, payload, useAuth = false }) => {
  const store = useStore();

  const apiCall = useAuth
    ? wretch("/api").auth(store.user?.token).url(path)[method](payload)
    : wretch("/api").url(path)[method](payload);

  return apiCall
    .json()
    .then(handleResponse)
    .catch((message) => {
      Notify.create({
        type: "negative",
        message,
      });
    });
};
