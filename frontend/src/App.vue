<script setup lang="ts">
import { RouterView, RouterLink } from "vue-router";
import { useDisplay } from "vuetify/lib/framework.mjs";
import AppPager from "./components/AppPager.vue";

const { smAndDown } = useDisplay();
</script>

<template>
  <v-layout>
    <v-app-bar>
      <v-app-bar-title>
        <RouterLink
          class="text-decoration-none font-weight-bold app-main-button"
          to="/"
          >WeatherApp</RouterLink
        >
        <!-- <v-btn to="/" color="blue-darken-4" variant="text">Weather App</v-btn> -->
      </v-app-bar-title>
      <v-spacer></v-spacer>
      <app-pager v-if="!smAndDown"></app-pager>
    </v-app-bar>
    <v-app-bar v-if="smAndDown" flat location="bottom" class="px-3">
      <app-pager withSpacer></app-pager>
    </v-app-bar>
    <v-main class="blue-grey-lighten-5" style="min-height: 300px">
      <RouterView />
    </v-main>
  </v-layout>
</template>

<style></style>
