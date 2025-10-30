import callApi from "src/assets/call-api";

export default async (form, contact) => {
  const valid = await form.validate();
  if (!valid) return;

  const response = await callApi({
    path: "/send-contact",
    method: "post",
    payload: contact,
  });

  return { response };
};
