<?php

namespace App\Services;

use App\Models\User;
use App\Models\Weather;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class WeatherService
{
    public static function validateParams(float $latitude, float $longitude)
    {
        if (abs($latitude) > 90) {
            throw new InvalidArgumentException('Invalid latitude value. Value must be between -90 and 90.');
        }
        if (abs($longitude) > 180) {
            throw new InvalidArgumentException('Invalid longitude value. Value must be between -180 and 180.');
        }
    }

    /**
     * @param float $latitude required
     * @param float $longitude required
     */
    public static function getWeather(float $latitude, float $longitude): object
    {
        self::validateParams($latitude, $longitude);

        $weather = self::weatherFromDB($latitude, $longitude);
        if ($weather) return $weather;

        $weather = self::weatherFromAPI($latitude, $longitude);
        return $weather;
    }

    public static  function weatherFromDB(float $latitude, float $longitude)
    {
        self::validateParams($latitude, $longitude);

        $pastHour = Carbon::now()->subHour();
        $query = Weather::where(['latitude' => $latitude, 'longitude' => $longitude])
            ->where('updated_at', '>=', $pastHour->toDateTimeString());

        $weather = $query->first();
        if ($weather) {
            return $weather;
        }

        return null;
    }

    public static function fetchWeatherData(float $latitude, float $longitude)
    {
        self::validateParams($latitude, $longitude);

        $response = Http::get(config('app.weather_api_url'), [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => config('app.weather_api_key'),
            'units' => config('app.weather_api_units')
        ])->throwIf(function ($response) {
            return $response->failed();
        });

        return $response->json();
    }

    public static function fetchLocationData(float $latitude, float $longitude)
    {
        self::validateParams($latitude, $longitude);

        $response = Http::get(config('app.location_api_url'), [
            'query' => "$latitude,$longitude",
            'access_key' => config('app.location_api_key'),
            'limit' => 1,
            'sun_module' => 1,
            'timezone_module' => 1
        ])->throwIf(function ($response) {
            return $response->failed();
        });

        return $response->json();
    }

    public static function fetchForecastData(float $latitude, float $longitude)
    {
        self::validateParams($latitude, $longitude);

        $response = Http::get(config('app.forecast_api_url'), [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => config('app.weather_api_key'),
            'units' => config('app.weather_api_units'),
            'cnt' => 8
        ])->throwIf(function ($response) {
            return $response->failed();
        });

        return $response->json();
    }

    public static function weatherFromAPI(float $latitude, float $longitude)
    {
        self::validateParams($latitude, $longitude);

        $data = self::fetchWeatherData($latitude, $longitude);
        $location = self::fetchLocationData($latitude, $longitude);
        $forecast = self::fetchForecastData($latitude, $longitude);

        try {
            $loc = $location['data'][0];
            $timezone_module = $loc['timezone_module'];
            $sun_module = $loc['sun_module'];
            unset($loc['timezone_module']);
            unset($loc['sun_module']);

            $weather = Weather::updateOrCreate(['longitude' => $longitude, 'latitude' => $latitude], [
                'city_name' => $loc['name'],
                'visibility' => $data['visibility'],
                'weather' => $data['weather'][0],
                'main' => $data['main'],
                'wind' => $data['wind'],
                'units' => config('app.weather_api_units'),
                'location' => $loc,
                'timezone_module' => $timezone_module,
                'sun_module' => $sun_module,
                'forecast' => $forecast['list'],
            ]);

            return $weather;
        } catch (Exception $e) {
            Log::error('Error in update or create weather data');
            throw new Exception("Error in update or create weather data");
        }

        return $data;
    }

    public static function getOldWeatherData(): array
    {
        $pastHour = Carbon::now()->subHour();

        // users with weather data
        $users = User::whereNotExists(function ($builder) use ($pastHour) {
            $builder->select('weathers.id')
                ->from('weathers')
                ->whereColumn('weathers.latitude', 'users.latitude')
                ->whereColumn('weathers.longitude', 'users.longitude');
        })->get();

        $data = [];
        foreach ($users as $user) {
            $data[] = new Weather(['latitude' => $user->latitude, 'longitude' => $user->longitude]);
        }

        $weathers = Weather::where('updated_at', '<', $pastHour->toDateTimeString())->get();
        foreach ($weathers as $weather) {
            $data[] = new Weather(['latitude' => $weather->latitude, 'longitude' => $weather->longitude]);
        }

        return $data;
    }
}
