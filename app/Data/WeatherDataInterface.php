<?php

namespace App\Data;

interface WeatherDataInterface
{
    /**
     * @param $wind
     */
    public function setWind($wind);

    /**
     * @param $main
     */
    public function setMain($main);
}
