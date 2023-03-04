<?php

namespace App\Console\Commands;

use App\Services\WeatherService;
use App\Jobs\UpdateWeather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOldWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:updatedata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update old weather data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info("Starting weather update");
        $oldWeatherData = WeatherService::getOldWeatherData();

        Log::info("Old weather data count: " . count($oldWeatherData));

        foreach ($oldWeatherData as $key => $weather) {
            Log::info("Dispatching weather update for " . $weather->id);
            UpdateWeather::dispatch($weather->latitude, $weather->longitude);
        }
    }
}
