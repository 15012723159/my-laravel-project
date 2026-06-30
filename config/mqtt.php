<?php

return [
    'host' => env('MQTT_HOST', '127.0.0.1'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', ''),
    'password' => env('MQTT_PASSWORD', ''),
    'client_id' => env('MQTT_CLIENT_ID', 'laravel-publisher'),
    'user_id' => env('MQTT_USER_ID', '20030'),
];