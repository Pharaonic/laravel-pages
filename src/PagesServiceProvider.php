<?php

namespace Pharaonic\Laravel\Pages;

use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    public function register()
    {
        // Publishes
        $this->publishes([
            __DIR__ . '/Migrations/2021_02_01_000006_create_pages_table.php'                   => database_path('migrations/2021_02_01_000006_create_pages_table.php'),
            __DIR__ . '/Migrations/2021_02_01_000007_create_page_translations_table.php'       => database_path('migrations/2021_02_01_000007_create_page_translations_table.php'),
            __DIR__ . '/Migrations/2021_02_01_000008_create_model_has_pages_table.php'         => database_path('migrations/2021_02_01_000008_create_model_has_pages_table.php'),
        ], ['pharaonic', 'laravel-pages']);
    }
}
