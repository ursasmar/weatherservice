<?php

namespace App\Services;

use App\Contracts\WeatherDataContract;
use App\Data\OpenWeatherMapData;
use App\Data\WeatherDataInterface;
use App\Exceptions\OpenWeatherMapException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class OpenWeatherMapService implements WeatherDataContract
{
    /** @var string */
    protected $apiKey;

    /** @var Client */
    protected $client;

    public function __construct()
    {
        $this->apiKey = config('api.open_weather_map.api_key');
        $this->client = new Client([
            'base_uri' => config('api.open_weather_map.base_uri'),
            'timeout' => 2.0,
            'http_errors' => true,
        ]);
    }

    /**
     * @param string $zipCode
     * @return OpenWeatherMapData
     * @throws OpenWeatherMapException
     */
    public function getWeather(string $zipCode): WeatherDataInterface
    {
        $data = [
            'zip' => $zipCode,
            'APPID' => $this->apiKey,
        ];

        $weather = $this->makeRequest($data);

        return $this->hydrateData($weather);
    }

    /**
     * @param $data
     * @return string
     * @throws OpenWeatherMapException
     */
    public function makeRequest(array $data): string
    {
        try {
            $response = $this->client->get("weather", ['query' => $data]);

            if ((int)$response->getStatusCode() !== 200) {
                throw new OpenWeatherMapException(
                    'Open Weather Map did not respond properly',
                    $response->getStatusCode()
                );
            }

            return (string)$response->getBody();

        } catch (RequestException $e) {
            throw new OpenWeatherMapException(
                'Open Weather Map caused an exception',
                $e->getResponse()->getStatusCode()
            );
        }
    }

    public function hydrateData(string $data): WeatherDataInterface
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->deserialize($data, OpenWeatherMapData::class, 'json');
    }
}