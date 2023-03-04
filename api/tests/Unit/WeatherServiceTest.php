<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use App\Models\User;
use InvalidArgumentException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $weather_data;
    protected $forecast_data;
    protected $location_data;
    protected $weather_api_url;
    protected $location_api_url;
    protected $forecast_api_url;

    // protected function setUp(): void
    // {
    //     // $this->weather_data = file_get_contents(base_path('tests/Unit/openweathermap.json'));
    //     // $this->forecast_data = file_get_contents(base_path('tests/Unit/openweathermap_forecast.json'));
    //     // $this->location_data = file_get_contents(base_path('tests/Unit/positionstack.json'));
    //     $this->weather_api_url = config('app.weather_api_url');
    //     $this->location_api_url = config('app.location_api_url');
    //     $this->forecast_api_url = config('app.forecast_api_url');
    // }

    public function test_weather_service_validate_latitude_value(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeatherService::validateParams(91, 0);
    }

    public function test_weather_service_validate_longitude_value(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeatherService::validateParams(0, 181);
    }

    public function test_get_old_weather_data(): void
    {
        $this->assertIsArray(WeatherService::getOldWeatherData());
    }

    public function test_weather_users_api(): void
    {
        $response = $this->get("/users");
        $response->assertStatus(200);
    }

    public function test_weather_users_weather_info_api(): void
    {
        User::factory()->create();
        $user = User::first();

        $weather_api_url = config('app.weather_api_url');
        $location_api_url = config('app.location_api_url');
        $forecast_api_url = config('app.forecast_api_url');

        $weather_data = file_get_contents(base_path('tests/Unit/openweathermap.json'));
        $forecast_data = file_get_contents(base_path('tests/Unit/openweathermap_forecast.json'));
        $location_data = file_get_contents(base_path('tests/Unit/positionstack.json'));

        Http::fake([
            $weather_api_url => Http::response($weather_data, 200),
            $forecast_api_url => Http::response($forecast_data, 200),
            $location_api_url => Http::response($location_data, 200),
        ]);

        $response = $this->get('/user/' . $user->id . '/weather');

        $response->assertStatus(201);
    }

    public function test_it_returns_weather_data(): void
    {
        $weather_api_url = config('app.weather_api_url');
        $location_api_url = config('app.location_api_url');
        $forecast_api_url = config('app.forecast_api_url');

        $weather_data = file_get_contents(base_path('tests/Unit/openweathermap.json'));
        $forecast_data = file_get_contents(base_path('tests/Unit/openweathermap_forecast.json'));
        $location_data = file_get_contents(base_path('tests/Unit/positionstack.json'));

        Http::fake([
            $weather_api_url => Http::response($weather_data, 200),
            $forecast_api_url => Http::response($forecast_data, 200),
            $location_api_url => Http::response($location_data, 200),
        ]);

        $weather = WeatherService::fetchWeatherData(0, 0);
        $this->assertIsArray($weather);

        $location = WeatherService::fetchLocationData(0, 0);
        $this->assertIsArray($location);

        $forecast = WeatherService::fetchForecastData(0, 0);
        $this->assertIsArray($forecast);
    }
}
