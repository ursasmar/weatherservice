<?php

namespace App\Contracts;


use App\Data\WeatherDataInterface;

interface WeatherDataContract
{
    /**
     * @param string $zipCode
     * @return WeatherDataInterface
     */
    public function getWeather(string $zipCode): WeatherDataInterface;

    /**
     * @param array $data
     * @return string
     */
    public function makeRequest(array $data): string;
}