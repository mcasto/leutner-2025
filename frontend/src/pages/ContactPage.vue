<template>
  <div style="height: 100vh;">
    <q-form ref="contactForm" greedy>
      <div class="column q-gutter-y-md q-pa-md">
        <q-input
          type="text"
          label="Name"
          v-model="contact.name"
          filled
          color="black"
          :rules="[rules.required]"
        ></q-input>
        <q-input
          type="email"
          label="Email"
          v-model="contact.email"
          filled
          color="black"
          :rules="[rules.required, rules.email]"
        ></q-input>
        <q-input
          type="text"
          label="Subject"
          v-model="contact.subject"
          filled
          color="black"
          :rules="[rules.special]"
        ></q-input>
        <q-input
          type="textarea"
          label="Message"
          v-model="contact.body"
          filled
          color="black"
          :rules="[rules.special]"
        ></q-input>

        <div class="flex justify-between">
          <q-checkbox
            label="Join Mailing List"
            v-model="contact.join"
            color="black"
          ></q-checkbox>

          <q-btn
            label="Submit"
            color="primary"
            class="text-black"
            @click="sendContact"
          ></q-btn>
        </div>
      </div>
    </q-form>
  </div>
</template>

<script setup>
  import { useStore } from "stores/store";
  const store = useStore();

  import { ref } from "vue";
  import validator from "email-validator";

  const contact = ref({
    name: null,
    email: null,
    subject: null,
    body: null,
    join: false,
  });

  const contactForm = ref(null);

  const rules = {
    required: (v) => !!v || "Required",
    email: (v) => validator.validate(v) || "Invalid Email",
    special: (v) =>
      contact.value.join || !!v || "Required if not joining mailing list",
  };

  const sendContact = () => {
    store.sendContact(contactForm.value, contact.value);
  };
</script>
