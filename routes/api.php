<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\PreviousController;
use App\Http\Controllers\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Profile\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('/profile')
->group(function(){
Route ::get('/',[ProfileController::class, 'getUserProfile'])->middleware('auth:sanctum');
 Route::get('/services',[ServicesController::class,'getUserServices'])->middleware('auth:sanctum');
});

Route::prefix('/previous')->group((function(){
    Route::post('/',[PreviousController::class,'addPrevious']);
    Route::get('/previous_works',[PreviousController::class,'getUserPrevious'])->middleware('auth:sanctum');
}));

Route::post('/register',[RegisterController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
Route::get('/cities',[CityController::class,'index']);
Route::get('/works',[WorkController::class ,'llal']);




Route::prefix('/services')
->group(function (){
    Route::get('/',[ ServicesController::class,'services']);
    Route::post('/store',[ServicesController::class,'store'])->middleware('auth:sanctum');
    Route::delete('/delete/{serviceId}',[ServicesController::class,'deleteService'])->middleware('auth:sanctum');


}); 