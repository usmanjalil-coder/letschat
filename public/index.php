<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

define('LARAVEL_START', microtime(true));

if(file_exists($maintenance =__DIR__.'/../storage/framework/maintenance.php')){
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

(require_once(__DIR__.'/../bootstrap/app.php'))

    ->handleRequest(Request::capture());

$executionTime = microtime(true) - LARAVEL_START;
// Log::info('Request execution Time is ------->  '. $executionTime);