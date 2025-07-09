<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default search engine that will be used by the
    | framework when performing searches. You may use any of the engines
    | listed below. You may even implement your own search engine.
    |
    */

    'driver' => env('SCOUT_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Search Engine Specifics
    |--------------------------------------------------------------------------
    |
    | Here you may specify which search engine you wish to use for each
    | model. This option overrides the default driver.
    |
    */

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://127.0.0.1:7700'),
        'key' => env('MEILISEARCH_KEY', null),
    ],
]; 