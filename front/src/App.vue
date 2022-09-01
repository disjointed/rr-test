<template>
  <v-app>
    <v-main>
      <v-container
        class="d-flex flex-column align-center justify-center fill-height"
        fluid
      >
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
  export default {
    name: 'App',
  };
</script>

<style lang="scss">
  body {
    user-select: none;
  }
</style>