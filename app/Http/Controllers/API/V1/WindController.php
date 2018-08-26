<?php

namespace App\Http\Controllers\API\V1;


use App\Contracts\WeatherDataContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShowWindRequest;
use Illuminate\Http\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class WindController extends Controller
{
    protected $weatherData;
    private $serializer;

    /**
     * WindController constructor.
     * @param WeatherDataContract $weatherData
     */
    public function __construct(WeatherDataContract $weatherData)
    {
        $this->weatherData = $weatherData;

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param ShowWindRequest $request
     * @param string $zipCode
     * @return Response
     */
    public function show(ShowWindRequest $request, string $zipCode): Response
    {
        $weatherData = $this->weatherData->getWeather($zipCode);

        $response = $this->serializer->normalize(
            $weatherData,
            'json',
            ['attributes' =>
                [
                    'windSpeed',
                    'windDirection'
                ]
            ]
        );

        return response($response, 200, ['Content-Type' => 'application/json']);
    }
}