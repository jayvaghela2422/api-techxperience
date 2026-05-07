<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerStoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;

Route::get('/customer-stories/public', [CustomerStoryController::class, 'publicIndex']);
Route::get('/customer-stories/public/{id}', [CustomerStoryController::class, 'publicShow']);

Route::get('/company', [CompanyController::class, 'publicShow']);

Route::post('/contact/send', [ContactController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer Story APIs
    Route::get('/customer-stories',        [CustomerStoryController::class, 'index']);
    Route::get('/customer-stories/{id}',   [CustomerStoryController::class, 'show']);
    Route::post('/customer-stories',       [CustomerStoryController::class, 'store']);
    Route::post('/customer-stories/{id}',  [CustomerStoryController::class, 'update']);
    Route::delete('/customer-stories/{id}',[CustomerStoryController::class, 'destroy']);
    Route::post('/admin/customer-stories/reorder',[CustomerStoryController::class, 'reorder']);

    // Company Profile APIs
    Route::post('/company',        [CompanyController::class, 'store']);      
    Route::post('/company/update', [CompanyController::class, 'update']);    
    Route::get('/company/admin',   [CompanyController::class, 'show']); 

    // Contact Messages APIs
    Route::get('/contact/messages', [ContactController::class, 'index']);
});