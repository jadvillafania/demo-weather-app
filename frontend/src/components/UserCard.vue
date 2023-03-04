<script setup lang="ts">
import { ref, onMounted } from "vue";
import UserModal from "@/components/UserModal.vue";
import moment from "moment";
import { useUserStore } from "@/stores/UserStore";
const store = useUserStore();

const { fetchUserWeather } = store;

interface Props {
  // eslint-disable-next-line no-undef
  user: User;
}

const userModalComponent = ref();

const props = defineProps<Props>();

function openModal() {
  if (props.user.status.error) return;
  if (props.user.weather == null) return;

  userModalComponent.value.openModal();
}

// eslint-disable-next-line no-undef
function loadData(user: User) {
  fetchUserWeather(user);
}

onMounted(() => {
  loadData(props.user);
});
</script>

<template>
  <v-card
    :data-user-latitude="user.latitude"
    :data-user-longitude="user.longitude"
    @click="openModal()"
    :class="{ 'has-fetch-error': user.status.error }"
    class="fill-height d-flex flex-column flex"
  >
    <v-progress-linear
      v-if="!user.weather && user.status.pending"
      indeterminate
    ></v-progress-linear>
    <v-card-title
      ><h5 class="text-center card-user-name">
        {{ user.name }}
      </h5>
    </v-card-title>
    <v-divider></v-divider>
    <v-card-text v-if="!!user.weather">
      <div class="d-flex fill-height flex-column space-between">
        <v-spacer></v-spacer>
        <h3 class="text-center">
          {{ user.weather?.city_name || "Unknown" }}
        </h3>
        <v-spacer></v-spacer>
        <v-sheet height="60px">
          <v-row no-gutters>
            <v-col
              cols="6"
              class="d-flex flex-column align-center justify-center"
            >
              <v-img
                :src="`http://openweathermap.org/img/wn/${user.weather?.weather.icon}@2x.png`"
                width="80px"
                class="image-drop-shadow filter-grayscale"
              ></v-img>
            </v-col>
            <v-col class="d-flex align-center justify-center">
              <h1 style="font-size: 36px" class="ml-5 font-weight-light">
                {{ Math.round(user.weather?.main.temp) }}&#176;
              </h1>
            </v-col>
          </v-row>
        </v-sheet>
        <label class="d-block text-caption text-uppercase mt-3 text-center">{{
          user.weather?.weather.description
        }}</label>
        <label class="d-block text-caption text-uppercase text-center">
          {{ moment(user.weather?.updated_at).format("MM/DD/YYYY") }}
        </label>
      </div>
    </v-card-text>

    <user-modal ref="userModalComponent" :user="user"></user-modal>
  </v-card>
</template>

<style scoped>
.has-fetch-error {
  position: relative;
}

.has-fetch-error .card-user-name {
  color: rgb(var(--v-theme-error)) !important;
}

.has-fetch-error::before {
  content: " ";
  background-color: rgb(var(--v-theme-error));
  position: absolute;
  height: 4px;
  width: 100%;
  z-index: 10;
}
</style>
