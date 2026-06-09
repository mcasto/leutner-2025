<template>
  <q-page class="q-pa-lg" style="max-width: 640px; margin: 0 auto">
    <!-- Password gate -->
    <q-card v-if="!authenticated" flat bordered class="q-pa-lg">
      <div class="text-h6 q-mb-md">Article Import</div>
      <q-input v-model="email" label="Email" outlined class="q-mb-md" @keyup.enter="authenticate" />
      <q-input
        v-model="password"
        label="Password"
        type="password"
        outlined
        class="q-mb-md"
        @keyup.enter="authenticate"
      />
      <div v-if="authError" class="text-negative text-caption q-mb-md">{{ authError }}</div>
      <q-btn label="Sign In" color="primary" :loading="loading" @click="authenticate" />
    </q-card>

    <!-- Import form -->
    <div v-else>
      <div class="text-h6 q-mb-md">Import Article</div>

      <q-btn-toggle
        v-model="form.type"
        :options="[
          { label: 'Medium', value: 'medium' },
          { label: 'Cuenca High Life', value: 'chl' },
        ]"
        class="q-mb-lg"
        @update:model-value="resetForm"
      />

      <q-file
        v-model="htmlFile"
        label="HTML File"
        accept=".html,.htm"
        outlined
        class="q-mb-md"
        @update:model-value="onFileSelected"
      >
        <template #prepend><q-icon name="mdi-file-code" /></template>
      </q-file>

      <q-file
        v-if="form.type === 'chl'"
        v-model="imageFiles"
        label="Images — select all files inside the _files folder (optional)"
        accept="image/*"
        multiple
        outlined
        class="q-mb-md"
      >
        <template #prepend><q-icon name="mdi-image-multiple" /></template>
      </q-file>

      <q-input v-model="form.id" label="Article ID (slug)" outlined class="q-mb-md" hint="e.g. my-article-title" />
      <q-input v-model="form.label" label="Title" outlined class="q-mb-md" />
      <q-input v-model="form.byline" label="Byline" outlined class="q-mb-md" />
      <q-input v-model="form.url" label="Original URL" outlined class="q-mb-md" />
      <q-input v-model="form.date" label="Date (YYYY-MM-DD)" outlined class="q-mb-md" />

      <q-select
        v-model="form.category_id"
        :options="categories"
        option-value="id"
        option-label="label"
        emit-value
        map-options
        label="Category"
        outlined
        class="q-mb-lg"
      />

      <q-btn
        label="Import Article"
        color="primary"
        :loading="loading"
        :disable="!canSubmit"
        @click="submit"
      />

      <q-banner
        v-if="result"
        :class="result.error ? 'bg-negative text-white' : 'bg-positive text-white'"
        class="q-mt-lg rounded-borders"
      >
        {{ result.error || result.message }}
      </q-banner>
    </div>
  </q-page>
</template>

<script setup>
import { ref, computed } from "vue";
import wretch from "wretch";

const SESSION_KEY = "leutner-admin-creds";

const saved = JSON.parse(sessionStorage.getItem(SESSION_KEY) || "null");
const email = ref(saved?.email || "");
const password = ref(saved?.password || "");
const authenticated = ref(false);
const authError = ref("");
const loading = ref(false);
const categories = ref([]);

const htmlFile = ref(null);
const imageFiles = ref([]);
const result = ref(null);

const form = ref({
  type: "medium",
  id: "",
  label: "",
  byline: "Carol E. Leutner",
  url: "",
  date: "",
  category_id: null,
});

const canSubmit = computed(
  () =>
    htmlFile.value &&
    form.value.id &&
    form.value.label &&
    form.value.url &&
    form.value.date &&
    form.value.category_id !== null
);

function authHeaders() {
  return { "X-Admin-Email": email.value, "X-Admin-Password": password.value };
}

async function authenticate() {
  authError.value = "";
  loading.value = true;
  try {
    const data = await wretch("/api/article-import/setup")
      .headers(authHeaders())
      .get()
      .json();
    sessionStorage.setItem(SESSION_KEY, JSON.stringify({ email: email.value, password: password.value }));
    categories.value = data.categories;
    authenticated.value = true;
  } catch {
    authError.value = "Invalid credentials.";
  } finally {
    loading.value = false;
  }
}

function resetForm() {
  htmlFile.value = null;
  imageFiles.value = [];
  form.value.id = "";
  form.value.label = "";
  form.value.url = "";
  form.value.date = "";
  form.value.category_id = null;
  result.value = null;
}

function onFileSelected(file) {
  if (!file || form.value.type !== "medium") return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const doc = new DOMParser().parseFromString(e.target.result, "text/html");
    const og = (prop) =>
      doc.querySelector(`meta[property="${prop}"]`)?.getAttribute("content") ?? "";

    form.value.label = og("og:title");
    form.value.url = og("og:url");
    const published = og("article:published_time");
    form.value.date = published
      ? published.split("T")[0]
      : new Date().toISOString().split("T")[0];
    form.value.id = slugify(form.value.label);
  };
  reader.readAsText(file);
}

async function submit() {
  result.value = null;
  loading.value = true;

  const fd = new FormData();
  fd.append("type", form.value.type);
  fd.append("file", htmlFile.value);
  fd.append("id", form.value.id);
  fd.append("label", form.value.label);
  fd.append("byline", form.value.byline);
  fd.append("url", form.value.url);
  fd.append("date", form.value.date);
  fd.append("category_id", form.value.category_id);

  if (form.value.type === "chl" && imageFiles.value?.length) {
    (Array.isArray(imageFiles.value) ? imageFiles.value : [imageFiles.value]).forEach((img) =>
      fd.append("images[]", img)
    );
  }

  try {
    const data = await wretch("/api/article-import")
      .headers(authHeaders())
      .body(fd)
      .post()
      .json();
    result.value = data;
    if (data.message) resetForm();
  } catch (err) {
    let message = "Import failed.";
    try {
      const body = err.json ?? (await err.response?.json());
      const firstValidation = body?.errors ? Object.values(body.errors)[0]?.[0] : null;
      message = firstValidation || body?.error || body?.message || message;
    } catch {
      // ignore parse errors
    }
    result.value = { error: message };
  } finally {
    loading.value = false;
  }
}

function slugify(text) {
  return text
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, "")
    .replace(/[\s-]+/g, "-")
    .replace(/^-|-$/g, "");
}

// Auto-authenticate if credentials are already in sessionStorage
if (saved) authenticate();
</script>
