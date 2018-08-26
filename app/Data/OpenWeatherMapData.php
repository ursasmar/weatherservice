<?php

namespace App\Data;


class OpenWeatherMapData implements WeatherDataInterface
{
    /** @var float */
    public $windSpeed;

    /** @var string */
    public $windDirection;

    /** @var float */
    public $temp;

    /** @var float */
    public $humidity;

    /** @var float */
    public $pressure;

    /** @var float */
    public $tempMin;

    /** @var float */
    public $tempMax;

    /**
     * @param $wind
     */
    public function setWind($wind)
    {
        $this->windSpeed = (float)$wind['speed'];
        $this->windDirection = $this->degreesToCardinal((float)$wind['deg']);
    }

    public function setMain($main)
    {
        $this->temp = $main['temp'];
        $this->humidity = $main['humidity'];
        $this->pressure = $main['pressure'];
        $this->tempMin = $main['temp_min'];
        $this->tempMax = $main['temp_max'];
    }

    /**
     * @param float $degrees
     * @return string
     */
    private function degreesToCardinal(float $degrees): string
    {
        $cardinals = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N'];

        return $cardinals[(int)round(($degrees % 369) / 45)];
    }
}