<template>
  <q-page class="q-pa-lg" style="max-width: 640px; margin: 0 auto">
    <div v-if="authenticated" class="row items-center justify-end q-mb-md">
      <span class="text-caption text-grey-7 q-mr-sm">{{ userName }}</span>
      <q-btn label="Sign Out" flat dense size="sm" :disable="loading" @click="signOut" />
    </div>

    <!-- Login -->
    <q-card v-if="!authenticated" flat bordered class="q-pa-lg">
      <div class="text-h6 q-mb-md">Article Import</div>
      <q-input v-model="email" label="Email" outlined class="q-mb-md" @keyup.enter="signIn" />
      <q-input
        v-model="password"
        label="Password"
        type="password"
        outlined
        class="q-mb-md"
        @keyup.enter="signIn"
      />
      <div v-if="authError" class="text-negative text-caption q-mb-md">{{ authError }}</div>
      <q-btn label="Sign In" color="primary" :loading="loading" @click="signIn" />
    </q-card>

    <!-- Post-import editor -->
    <div v-else-if="editing">
      <div class="text-h6">Edit Article</div>
      <div class="text-caption text-grey-7 q-mb-md">{{ editing.id }}</div>

      <q-editor
        v-model="editing.html"
        :toolbar="editorToolbar"
        :definitions="editorDefinitions"
        min-height="24rem"
        class="q-mb-md"
      />

      <q-btn label="Save" color="primary" :loading="loading" @click="saveEdits" />
      <q-btn label="Done" flat class="q-ml-sm" :disable="loading" @click="finishEditing" />

      <q-banner
        v-if="result"
        :class="result.error ? 'bg-negative text-white' : 'bg-positive text-white'"
        class="q-mt-lg rounded-borders"
      >
        {{ result.error || result.message }}
      </q-banner>
    </div>

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
import MarkdownIt from "markdown-it";

const SESSION_KEY = "leutner-admin-token";
const md = new MarkdownIt({ html: true });

const editorToolbar = [
  ["bold", "italic", "underline", "strike"],
  ["h2", "h3", "p", "quote"],
  ["unordered", "ordered", "link", "hr"],
  ["removeFormat", "undo", "redo"],
  ["viewsource"],
];

// Re-skin Quasar's built-in source toggle as a "code" button
const editorDefinitions = {
  viewsource: { icon: "mdi-code-tags", tip: "Edit HTML" },
};

// Older versions of this page stored the raw email/password here
sessionStorage.removeItem("leutner-admin-creds");

const saved = JSON.parse(sessionStorage.getItem(SESSION_KEY) || "null");
const token = ref(saved?.token || "");
const userName = ref(saved?.name || "");
const email = ref("");
const password = ref("");
const authenticated = ref(false);
const authError = ref("");
const loading = ref(false);
const categories = ref([]);

const htmlFile = ref(null);
const imageFiles = ref([]);
const result = ref(null);
const editing = ref(null); // { id, html } once an import succeeds

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
  return { Authorization: `Bearer ${token.value}`, Accept: "application/json" };
}

async function signIn() {
  authError.value = "";
  loading.value = true;
  try {
    const data = await wretch("/api/login")
      .headers({ Accept: "application/json" })
      .post({ email: email.value, password: password.value })
      .json();
    token.value = data.token;
    userName.value = data.user.name;
    sessionStorage.setItem(SESSION_KEY, JSON.stringify({ token: token.value, name: userName.value }));
    password.value = "";
    await loadSetup();
  } catch (err) {
    authError.value = await errorMessage(err, "Sign in failed.");
  } finally {
    loading.value = false;
  }
}

async function loadSetup() {
  try {
    const data = await wretch("/api/article-import/setup").headers(authHeaders()).get().json();
    categories.value = data.categories;
    authenticated.value = true;
  } catch (err) {
    if (!expireSession(err)) authError.value = await errorMessage(err, "Could not load categories.");
  }
}

async function signOut() {
  loading.value = true;
  try {
    await wretch("/api/logout").headers(authHeaders()).post().res();
  } catch {
    // token may already be expired/revoked — sign out locally regardless
  } finally {
    clearSession();
    editing.value = null;
    result.value = null;
    loading.value = false;
  }
}

function clearSession() {
  token.value = "";
  userName.value = "";
  authenticated.value = false;
  sessionStorage.removeItem(SESSION_KEY);
}

// On a 401, drop back to the login card. Unsaved editor content is kept so it
// can be saved after signing back in.
function expireSession(err) {
  if (err?.status !== 401) return false;
  clearSession();
  authError.value = "Your session has expired. Please sign in again.";
  return true;
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
    resetForm();
    result.value = data;
    editing.value = { id: data.id, html: md.render(data.markdown) };
  } catch (err) {
    if (!expireSession(err)) result.value = { error: await errorMessage(err, "Import failed.") };
  } finally {
    loading.value = false;
  }
}

async function saveEdits() {
  result.value = null;
  loading.value = true;

  try {
    result.value = await wretch(`/api/article-import/${editing.value.id}`)
      .headers(authHeaders())
      .put({ html: editing.value.html })
      .json();
  } catch (err) {
    if (!expireSession(err)) result.value = { error: await errorMessage(err, "Save failed.") };
  } finally {
    loading.value = false;
  }
}

function finishEditing() {
  editing.value = null;
  result.value = null;
}

async function errorMessage(err, fallback) {
  try {
    const body = err.json ?? (await err.response?.json());
    const firstValidation = body?.errors ? Object.values(body.errors)[0]?.[0] : null;
    return firstValidation || body?.error || body?.message || fallback;
  } catch {
    return fallback;
  }
}

function slugify(text) {
  return text
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, "")
    .replace(/[\s-]+/g, "-")
    .replace(/^-|-$/g, "");
}

// Resume the session if a token is already in sessionStorage
if (token.value) loadSetup();
</script>
