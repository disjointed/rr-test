<template>
  <v-btn
    color="primary"
    :to="{
      name: 'ads',
    }"
    depressed
    x-large
  >
    Объявления
  </v-btn>
</template>

<script>
  import AdList from '../components/AdList';

  export default {
    name: 'HomeView',
    components: {
      AdList,
    },
  }
</script>
