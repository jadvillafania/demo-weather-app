interface Pagination {
  data: Array<User> = [];
  current_page: number = 1;
  first_page_url: string;
  last_page_url: string;
  last_page: number;
  per_page: number = 20;
  from: number = 0;
  to: number = 0;
  // hasNextPage: boolean = false;
  // hasPreviousPage: boolean = false;
  total: number = 0;
  status: Status;
}

interface Status {
  error: boolean = false;
  success: boolean = false;
  pending: boolean = true;
  message: string = "";
}

interface User {
  id: number;
  name: string;
  longitude: number;
  latitude: number;
  weather: Weather | null;
  status: Status;
}

interface Weather {
  city_name: string;
  updated_at: Date;
  main: MainInfo;
  weather: WeatherInfo;
  wind: WindInfo;
  units: string;
  timezone_module: TimezoneModule;
  sun_module: SunModule;
  forecast: Array<ForecastInfo>;
}

interface SunModule {
  rise: SunModuleInfo;
  set: SunModuleInfo;
}

interface SunModuleInfo {
  time: number;
  astronomical: number;
  civil: number;
  nautical: number;
}

interface ForecastInfo {
  dt: number;
  dt_txt: string;
  main: MainInfo;
  weather: Array<WeatherInfo>;
  wind: WindInfo;
}

interface TimezoneModule {
  name: string;
  offset_sec: number;
  offset_string: string;
}

interface WeatherInfo {
  id: number;
  main: string;
  description: string;
  icon: string;
}

interface MainInfo {
  temp: number;
  feels_like: number;
  temp_min: number;
  temp_max: number;
  pressure: number;
  humidity: number;
  sea_level: number;
  grnd_level: number;
}

interface WindInfo {
  speed: number;
  deg: number;
  gust: number;
}
