<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useUserStore } from "@/stores/UserStore";
import { storeToRefs } from "pinia";
const store = useUserStore();
const { pagination } = storeToRefs(store);
const { fetchData, changePageSize } = store;

interface Props {
  // eslint-disable-next-line no-undef
  withSpacer?: boolean;
}

defineProps<Props>();

const page = ref(1);
const pageSize = ref();

const controller = ref(new AbortController());

function loadData() {
  controller.value.abort();
  controller.value = new AbortController();
  fetchData(page.value, controller.value.signal);
}

function updatePageSize() {
  page.value = 1;
  controller.value.abort();
  controller.value = new AbortController();
  changePageSize(pageSize.value);
  fetchData(1, controller.value.signal);
}

onMounted(() => {
  pageSize.value = pagination.value.per_page;
  fetchData();
});

const pageSizes = [5, 10, 15, 20];
</script>

<template>
  <v-sheet class="mr-3">
    <v-select
      density="compact"
      hide-details
      single-line
      v-model="pageSize"
      :items="pageSizes"
      variant="solo"
      @update:model-value="updatePageSize"
    >
    </v-select>
  </v-sheet>
  <v-spacer v-if="withSpacer"></v-spacer>
  <v-pagination
    v-model="page"
    :length="pagination.last_page"
    :total-visible="1"
    density="compact"
    variant="tonal"
    @next="loadData()"
    @prev="loadData()"
  ></v-pagination>
</template>

<style></style>
