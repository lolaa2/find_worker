<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\PreviousController;
use App\Http\Controllers\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\WorkerRequestsController;

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
    Route::post('/',[PreviousController::class,'addPrevious'])->middleware('auth:sanctum');
    Route::get('/{id?}',[PreviousController::class,'getUserPrevious'])->middleware('auth:sanctum');
    Route::post('/{previousId}',[PreviousController::class,'updatePrevious'])->middleware('auth:sanctum');
    Route::delete('/{previousId}',[PreviousController::class,'deleteService'])->middleware('auth:sanctum');

}));

Route::post('/register/{type?}',[RegisterController::class,'register']);
Route::post('/login/{type?}',[LoginController::class,'login']);
Route::get('/cities',[CityController::class,'index']);
Route::get('/works',[WorkController::class ,'llal']);
Route::get('/categories',[CategoryController::class,'index']);




Route::prefix('/services')
->group(function (){
    Route::get('/',[ ServicesController::class,'services'])->middleware('auth:sanctum');
    Route::post('/store',[ServicesController::class,'store'])->middleware('auth:sanctum');
    Route::delete('/delete/{serviceId}',[ServicesController::class,'deleteService'])->middleware('auth:sanctum');
    Route::post('/update/{service_id}', [ServicesController::class, 'updateService'])->middleware('auth:sanctum');
    Route::post('/request',[ServiceRequestController::class,'storeRequest'])->middleware('auth:customer_api');
    Route::get('customer/requests',[ServiceRequestController::class,'getCustomerRequests'])->middleware('auth:customer_api');
    Route::get('worker/requests',[ServiceRequestController::class,'getWorkerRequests'])->middleware('auth:worker_api');
    Route::get('company/requests',[ServiceRequestController::class,'getCompanyRequests'])->middleware('auth:company_api');
    Route::post('worker/requests/accept',[ServiceRequestController::class,'accept'])->middleware('auth:worker_api');
    Route::get('customer/requests/complete',[ServiceRequestController::class,'complete'])->middleware('auth:customer_api');

    Route::delete('/delete/images/{imageId}',[ServicesController::class,'deleteImage'])->middleware('auth:sanctum');


});