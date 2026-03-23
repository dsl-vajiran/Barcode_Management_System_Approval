<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use App\Database\HanaConnection;
use App\Database\HanaConnector;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom HANA database driver
        Connection::resolverFor('hana', function ($connection, $database, $prefix, $config) {
            return new HanaConnection($connection, $database, $prefix, $config);
        });

        // Register HANA connector
        $this->app['db']->extend('hana', function ($config, $name) {
            $connector = new HanaConnector();
            $connection = $connector->connect($config);
            return new HanaConnection($connection, $config['database'], $config['prefix'] ?? '', $config);
        });

        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();
    }
}
