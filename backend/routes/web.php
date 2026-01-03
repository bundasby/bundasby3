<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Bunda PAUD Kota Surabaya API',
        'version' => '1.0.0',
        'status' => 'running',
        'message' => 'Selamat datang di API Bunda PAUD Kota Surabaya'
    ]);
});
