<?php

namespace App\Jobs;

use App\Services\WeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateWeather implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $uniqueFor = 3600;

    // public function uniqueId(): string
    // {
    //     return $this->weather->id;
    // }
    public float $latitude;
    public float $longitude;

    /**
     * Create a new job instance.
     */
    public function __construct(
        float $latitude,
        float $longitude
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Weather location is ' . $this->latitude . ', ' . $this->longitude);
        WeatherService::weatherFromAPI($this->latitude, $this->longitude);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Send user notification of failure, etc...
        Log::error('Error executing job. Weather location is ' . $this->latitude . ', ' . $this->longitude);
    }
}
