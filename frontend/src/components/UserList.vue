<script setup lang="ts">
import { ref } from "vue";
import { storeToRefs } from "pinia";
import { useUserStore } from "@/stores/UserStore";
import UserCard from "@/components/UserCard.vue";
const store = useUserStore();

const { pagination } = storeToRefs(store);
const { fetchData } = store;

const snackbar = ref(false);

function loadData() {
  snackbar.value = false;
  fetchData().catch(() => {
    snackbar.value = true;
  });
}
</script>

<template>
  <v-container fluid>
    <v-snackbar v-model="snackbar" color="error" :timeout="-1">
      <span>There was a problem trying to fetch data.</span>
      <template v-slot:actions>
        <v-btn variant="text" @click="loadData"> Retry </v-btn>
      </template>
    </v-snackbar>
    <v-row
      v-if="pagination.status.pending"
      class="fill-height"
      align-content="center"
      justify="center"
    >
      <v-col cols="8">
        <v-progress-linear class="mt-3" indeterminate></v-progress-linear>
      </v-col>
    </v-row>

    <v-row v-else align="stretch">
      <v-col
        v-for="user in pagination.data"
        :key="user.id"
        cols="12"
        sm="6"
        md="3"
        xl="2"
      >
        <!-- <v-lazy class="fill-height"> -->
        <user-card :user="user"></user-card>
        <!-- </v-lazy> -->
      </v-col>
    </v-row>
  </v-container>
</template>
