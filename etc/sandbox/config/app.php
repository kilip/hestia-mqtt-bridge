<?php

return [
    'env' => 'development',
    'providers' => [
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Hestia\MqttGateway\ServiceProvider::class
    ],
    'aliases' => [
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'Log' => Illuminate\Support\Facades\Log::class,
    ]
];
