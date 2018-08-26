<?php

namespace App\Data;


class OpenWeatherMapData implements WeatherDataInterface
{
    /** 
     * @Groups({"complete", "wind"}) 
     */
    public $windSpeed;

    /** 
     * @Groups({"complete", "wind"}) 
     */
    public $windDirection;

    /** 
     * @Groups({"complete", "main"}) 
     */
    public $temp;

    /** 
     * @Groups({"complete", "main"}) 
     */
    public $humidity;

    /** 
     * @Groups({"complete", "main"}) 
     */
    public $pressure;

    /** 
     * @Groups({"complete", "main"}) 
     */
    public $tempMin;

    /** 
     * @Groups({"complete", "main"}) 
     */
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

        return $cardinals[ (int)round(($degrees % 369) / 45) ];
    }
}