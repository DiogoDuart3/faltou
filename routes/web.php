<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/falta-eletricidade', 'outages.power');
Route::view('/falta-agua', 'outages.water');

Route::get('/sitemap.xml', function () {
    return response()
        ->view('sitemap', [], 200)
        ->header('Content-Type', 'application/xml');
});
