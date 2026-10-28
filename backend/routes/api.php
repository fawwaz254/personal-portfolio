<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;

Route::get('/projects', [PortfolioController::class, 'projects']);
Route::get('/test-cases', [PortfolioController::class, 'testCases']);
Route::post('/contact', [PortfolioController::class, 'contact']);
