<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Load Modular Routes
|--------------------------------------------------------------------------
*/

foreach (glob(__DIR__ . '/web/*.php') as $file) {
    require $file;
}