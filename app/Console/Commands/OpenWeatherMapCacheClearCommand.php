<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class OpenWeatherMapCacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:clear {zipCode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the Open Weather Map cache for a given zip code.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle()
    {
        $zipCode = $this->argument('zipCode');

        if (!preg_match('/^[0-9]{5}$/', $zipCode)) {
            $this->error('Zip Code must be 5 digits');

            return;
        }

        Cache::delete("weather:{$zipCode}");

        $this->info("Weather Cache for weather:{$zipCode} has been cleared");
    }
}
