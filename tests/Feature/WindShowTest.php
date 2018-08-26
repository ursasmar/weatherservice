<?php

namespace Tests\Feature;

use App\Contracts\WeatherDataContract;
use App\Services\OpenWeatherMapService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WindShowTest extends TestCase
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

        $weatherDataContract = $this->getMockBuilder(OpenWeatherMapService::class)
            ->setMethods(['makeRequest'])
            ->getMock();

        $weatherDataContract
            ->method('makeRequest')
            ->willReturn($owmData);

        $this->app->instance(WeatherDataContract::class, $weatherDataContract);
    }

    /**
     * @test
     */
    public function can_show_wind_direction_and_speed()
    {
        $response = $this->json('GET', route('api.v1.wind.show', ['zipCode' => 89101]))
                ->assertStatus(200);

        $this->assertEquals(21.96, data_get($response->json(), 'windSpeed'));
        $this->assertEquals('W', data_get($response->json(), 'windDirection'));
    }

    /**
     * @test
     */
    public function fails_on_invalid_zip_code()
    {
        $response = $this->json('GET', route('api.v1.wind.show', ['zipCode' => 8910]))
            ->assertStatus(422);

        $this->assertEquals('A US Zip Code is required', data_get($response->json(), 'zipCode.0'));
    }

    /**
     * @test
     */
    public function fails_on_missing_zip_code()
    {
        $this->json('GET', route('api.v1.wind.show', ['zipCode' => null]))
            ->assertStatus(404);
    }
}
