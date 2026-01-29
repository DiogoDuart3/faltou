<?php

use App\Http\Controllers\OutageCommentController;
use App\Http\Controllers\OutageReportController;
use Illuminate\Support\Facades\Route;

Route::get('/reports', [OutageReportController::class, 'index']);
Route::post('/reports', [OutageReportController::class, 'store'])->middleware('throttle:reports');

Route::get('/comments', [OutageCommentController::class, 'index']);
Route::post('/comments', [OutageCommentController::class, 'store'])->middleware('throttle:comments');
