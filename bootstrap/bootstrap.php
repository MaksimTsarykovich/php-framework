<?php

use App\Providers\EventServiceProvider;

$providers = [
    EventServiceProvider::class,
];

foreach ($providers as $provider) {
    $provider = $container->get($provider);

    $provider->register();
}