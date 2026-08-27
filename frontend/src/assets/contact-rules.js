import validator from "email-validator";

const required = (v) => (!!v ? true : "Required");

const email = (v) => (validator.validate(v) ? true : "Invalid Email");

export default { required, email };

export const reasonsForFailure = (contact) => {
  const reasons = [];

  const nameCheck = required(contact.name);
  if (nameCheck !== true) reasons.push(`Name: ${nameCheck}`);

  const emailRequiredCheck = required(contact.email);
  if (emailRequiredCheck !== true) {
    reasons.push(`Email: ${emailRequiredCheck}`);
  } else {
    const emailCheck = email(contact.email);
    if (emailCheck !== true) reasons.push(`Email: ${emailCheck}`);
  }

  const subjectCheck = required(contact.subject);
  if (subjectCheck !== true) reasons.push(`Subject: ${subjectCheck}`);

  const bodyCheck = required(contact.body);
  if (bodyCheck !== true) reasons.push(`Message: ${bodyCheck}`);

  return reasons;
};
