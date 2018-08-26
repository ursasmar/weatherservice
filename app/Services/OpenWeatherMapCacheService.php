<?php

namespace App\Services;


use App\Contracts\WeatherDataContract;
use App\Data\OpenWeatherMapData;
use App\Data\WeatherDataInterface;
use Illuminate\Support\Facades\Cache;

class OpenWeatherMapCacheService implements WeatherDataContract
{
    protected $weatherDataContract;

    public function __construct(WeatherDataContract $weatherDataContract)
    {
        $this->weatherDataContract = $weatherDataContract;
    }

    /**
     * @param string $zipCode
     * @return OpenWeatherMapData
     */
    public function getWeather(string $zipCode): WeatherDataInterface
    {
        return Cache::remember("weather:{$zipCode}", 15, function () use ($zipCode) {
            return $this->weatherDataContract->getWeather($zipCode);
        });
    }

    /**
     * @param $data
     * @return string
     */
    public function makeRequest(array $data): string
    {
        $this->weatherDataContract->makeRequest($data);
    }
}