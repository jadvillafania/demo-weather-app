<script setup lang="ts">
import { ref } from "vue";
import moment from "moment";
import { useDisplay } from "vuetify/lib/framework.mjs";
const { smAndDown } = useDisplay();

const dialog = ref(false);

interface Props {
  // eslint-disable-next-line no-undef
  user: User;
}

defineProps<Props>();

function openModal() {
  dialog.value = true;
}
defineExpose({ openModal });

function getTime(
  unixTime: number | string | undefined,
  timezoneShift: number | undefined
) {
  let time = moment
    .utc(unixTime, "X")
    .add(timezoneShift, "seconds")
    .format("hh:mmA");
  return time;
}
</script>

<template>
  <v-dialog
    v-model="dialog"
    :scrollable="smAndDown"
    :max-height="600"
    :width="smAndDown ? 'auto' : '80%'"
  >
    <v-card>
      <v-card-title>
        <h5>{{ user.name }}</h5>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-row no-gutters>
          <v-col
            class="d-flex flex-column flex align-center"
            cols="12"
            md="6"
            :class="!smAndDown ? 'pr-3' : ''"
          >
            <label class="text-caption">{{
              moment(user.weather?.updated_at).format("dddd, D MMMM YYYY")
            }}</label>
            <h4 class="mt-10 font-weight-bold text-uppercase">
              {{ user.weather?.city_name }}
            </h4>
            <label class="text-overline">{{
              user.weather?.weather.description
            }}</label>
            <v-img
              :src="`http://openweathermap.org/img/wn/${user.weather?.weather.icon}@2x.png`"
              width="80px"
              class="image-drop-shadow mt-n4 filter-grayscale"
            ></v-img>
            <h1 style="font-size: 80px" class="font-weight-light mt-n6">
              {{ Math.round(user.weather?.main.temp || 0) }}&deg;
            </h1>
            <div class="d-flex flex-row">
              <div class="d-flex flex-column align-center">
                <span class="text-caption">max</span>
                <b>{{ Math.round(user.weather?.main.temp_max || 0) }}&deg;</b>
              </div>
              <v-divider vertical class="mx-3" :thickness="2"></v-divider>
              <div class="d-flex flex-column align-center">
                <span class="text-caption">min</span>
                <b>{{ Math.round(user.weather?.main.temp_min || 0) }}&deg;</b>
              </div>
            </div>
            <v-divider width="100%" class="my-4"></v-divider>
            <v-row>
              <v-col
                cols="3"
                class="d-flex flex-column flex align-center justify-center"
              >
                <v-icon size="25" icon="mdi-white-balance-sunny"></v-icon>
                <span class="text-caption mt-1">{{
                  getTime(
                    user.weather?.sun_module.rise.time,
                    user.weather?.timezone_module.offset_sec
                  )
                }}</span>
              </v-col>
              <v-col
                cols="3"
                class="d-flex flex-column flex align-center justify-center"
              >
                <v-icon size="25" icon="mdi-weather-night"></v-icon>
                <span class="text-caption mt-1">{{
                  getTime(
                    user.weather?.sun_module.rise.time,
                    user.weather?.timezone_module.offset_sec
                  )
                }}</span>
              </v-col>
              <v-col
                cols="3"
                class="d-flex flex-column flex align-center justify-center"
              >
                <v-icon size="25" icon="mdi-weather-windy"></v-icon>
                <span class="text-caption mt-1"
                  >{{ user.weather?.wind.speed }}m/s</span
                >
              </v-col>
              <v-col
                cols="3"
                class="d-flex flex-column flex align-center justify-center"
              >
                <v-icon size="25" icon="mdi-water-percent"></v-icon>
                <span class="text-caption mt-1"
                  >{{ user.weather?.main.humidity }}%</span
                >
              </v-col>
            </v-row>
          </v-col>
          <v-col
            cols="12"
            md="6"
            class="d-flex flex flex-column align-center justify-center"
            :class="!smAndDown ? 'pl-3' : ''"
          >
            <v-divider width="100%" v-if="smAndDown" class="my-4"></v-divider>
            <h5>Forecast for today</h5>
            <v-table density="comfortable">
              <tbody>
                <tr v-for="item in user.weather?.forecast" :key="item.dt">
                  <td class="text-caption">
                    {{
                      moment(item.dt_txt)
                        .add(
                          user.weather?.timezone_module.offset_sec,
                          "seconds"
                        )
                        .format("HH:mm")
                    }}
                  </td>
                  <td class="text-caption text-capitalize">
                    {{ item.weather[0].description }}
                  </td>
                  <td class="text-caption">{{ item.main.temp }}&deg;</td>
                </tr>
              </tbody>
            </v-table>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-btn block @click="dialog = false">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
