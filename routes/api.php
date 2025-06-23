<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\SearchItemController;
use App\Http\Controllers\Api\SaveController;
use App\Http\Controllers\Api\ClassAndCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login',[LoginController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/locations/station',[LocationController::class,'getStations']);
    Route::get('/locations/section',[LocationController::class,'getSections']);
    Route::get('/locations/shelf',[LocationController::class,'getShelves']);
    Route::get('/locations',[LocationController::class,'get']);
    Route::get("/items",[SearchItemController::class,'search']);
    Route::post('saveWeightOrCount',[SaveController::class,'save']);
    Route::post('create-shelf',[LocationController::class,'store']);
    Route::get('/classes',[ClassAndCategoryController::class,'getClasses']);
    Route::get('/categories',[ClassAndCategoryController::class,'getCategories']);
});
