import callApi from "src/assets/call-api";
import { reasonsForFailure } from "src/assets/contact-rules";
import { Dialog, Notify } from "quasar";

const escapeHtml = (str) =>
  str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");

const showFailureDialog = (html) => {
  Dialog.create({
    title: "Message Not Sent",
    message: html,
    html: true,
    persistent: true,
    ok: {
      label: "OK",
      color: "negative",
      unelevated: true,
    },
  });
};

export default async (form, contact) => {
  const valid = await form.validate();

  if (!valid) {
    const reasons = reasonsForFailure(contact);

    try {
      await callApi({
        path: "/contact-failure",
        method: "post",
        payload: { reason: reasons.join("; "), ...contact },
        silent: true,
      });
    } catch (e) {
      // Best-effort logging only - don't let a failed log call mask the dialog below.
    }

    showFailureDialog(
      reasons.length
        ? `<p>Please fix the following and try again:</p><ul>${reasons
            .map((reason) => `<li>${escapeHtml(reason)}</li>`)
            .join("")}</ul>`
        : "<p>Please check the form and try again.</p>",
    );

    return;
  }

  try {
    await callApi({
      path: "/send-contact",
      method: "post",
      payload: contact,
      silent: true,
    });

    Notify.create({
      type: "positive",
      message: "Thanks! Your message has been sent.",
    });

    // Reset validation first so clearing the values below doesn't
    // immediately re-trigger required-field errors.
    form.resetValidation();
    Object.assign(contact, {
      name: null,
      email: null,
      subject: null,
      body: null,
      join: false,
    });
  } catch (message) {
    const reason =
      typeof message === "string"
        ? message.replace(/^Error:\s*/, "")
        : "Something went wrong sending your message. Please try again later.";

    showFailureDialog(`<p>${escapeHtml(reason)}</p>`);
  }
};
