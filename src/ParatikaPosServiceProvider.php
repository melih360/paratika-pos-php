<?php

namespace melih360\ParatikaPosPhp;

use Illuminate\Support\ServiceProvider;

class ParatikaPosServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Config dosyasını merge et
        $this->mergeConfigFrom(__DIR__.'/../config/paratika.php', 'paratika');
    }

    public function boot()
    {
        // Config dosyasını publish etmek için
        $this->publishes([
            __DIR__.'/../config/paratika.php' => config_path('paratika.php'),
        ], 'paratika-config');
    }
}
