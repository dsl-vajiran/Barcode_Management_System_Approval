<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$service = new \App\Services\HanaService();
$items = $service->searchItems(null, 10, 0);
print_r($items);
