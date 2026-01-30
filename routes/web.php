<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/falta-eletricidade', 'outages.power');
Route::view('/falta-agua', 'outages.water');

Route::view('/guia-falta-eletricidade', 'pages.guide-power');
Route::view('/guia-falta-agua', 'pages.guide-water');
Route::view('/guia-kit-emergencia', 'pages.guide-kit');
Route::view('/guia-indemnizacao', 'pages.guide-compensation');
Route::view('/contactos', 'pages.contacts');

Route::get('/sitemap.xml', function () {
    return response()
        ->view('sitemap', [], 200)
        ->header('Content-Type', 'application/xml');
});
