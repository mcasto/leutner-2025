<template>
  <q-img
    :src="card.path"
    :ratio="card.ratio"
    fit="contain"
    position="top"
  ></q-img>
</template>

<script>
  export default {
    name: "DisplayPhotos",
    props: ["card"],
  };
</script>
