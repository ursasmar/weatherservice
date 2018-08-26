<?php

namespace Tests\Unit;

use App\Contracts\WeatherDataContract;
use App\Data\OpenWeatherMapData;
use App\Services\OpenWeatherMapCacheService;
use App\Services\OpenWeatherMapService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherDataCacheTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $owmData = '{"coord":{"lon":-122.09,"lat":37.39},
                      "sys":{"type":3,"id":168940,"message":0.0297,"country":"US","sunrise":1427723751,"sunset":1427768967},
                      "weather":[{"id":800,"main":"Clear","description":"Sky is Clear","icon":"01n"}],
                      "base":"stations",
                      "main":{"temp":285.68,"humidity":74,"pressure":1016.8,"temp_min":284.82,"temp_max":286.48},
                      "wind":{"speed":21.96,"deg":285.001},
                      "clouds":{"all":0},
                      "dt":1427700245,
                      "id":0,
                      "name":"Mountain View",
                      "cod":200}';

        $weatherDataContract = $this->getMockBuilder(OpenWeatherMapCacheService::class)
            ->setMethods(['makeRequest'])
            ->setConstructorArgs([app(OpenWeatherMapService::class)])
            ->getMock();

        $weatherDataContract
            ->method('makeRequest')
            ->willReturn($owmData);

        $this->app->instance(WeatherDataContract::class, $weatherDataContract);
    }

    /**
     * @test
     */
    public function weather_service_cache_returns_wind_data()
    {
        $weatherData = new OpenWeatherMapData();
        $weatherData->setWind(['speed' => 21.96, 'deg' => 285.001]);

        Cache::shouldReceive('remember')
            ->once()
            ->with('weather:89101', 15, \Closure::class)
            ->andReturn($weatherData);

        $weatherService = app(WeatherDataContract::class)->getWeather(89101);

        $this->assertEquals(21.96, $weatherService->windSpeed);
        $this->assertEquals('W', $weatherService->windDirection);
    }
}
