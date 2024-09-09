<?php

return [
    'db' => [
        'driver' => 'sqlite',
        'sqlite' => [
            'database' => __DIR__ . '/../database.sqlite',
        ],
        'mysql' => [
            'host' => 'localhost',
            'dbname' => 'auction',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
    ],
];
