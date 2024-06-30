<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use Slim\App;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (App $app, Settings $settings) {
    $capsule = new Capsule();
    $databaseConfig = $settings->get('database');
    $capsule->addConnection([
        'driver' => $databaseConfig['driver'],
        'host' => $databaseConfig['host'],
        'database' => $databaseConfig['database'],
        'username' => $databaseConfig['username'],
        'password' => $databaseConfig['password'],
        'charset' => $databaseConfig['charset'],
        'collation' => $databaseConfig['collation'],
        'prefix' => $databaseConfig['prefix'],
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
};
