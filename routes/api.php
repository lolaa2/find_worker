<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\ServicesCompanyController;
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
use App\Models\Company;

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
    Route::post('/add',[PreviousController::class,'addPrevious'])->middleware('auth:sanctum');
    Route::get('/show{id?}',[PreviousController::class,'getUserPrevious'])->middleware('auth:sanctum');
    Route::post('/update{previousId}',[PreviousController::class,'updatePrevious'])->middleware('auth:sanctum');
    Route::delete('/delete{previousId}',[PreviousController::class,'deletePrevious'])->middleware('auth:sanctum');

}));

Route::post('/register/{type?}',[RegisterController::class,'register']);
Route::post('/login/{type?}',[LoginController::class,'login']);
Route::get('/cities',[CityController::class,'index']);
Route::get('/companies',[CompanyController::class,'companyFetch']);
Route::get('/works',[WorkController::class ,'llal']);
Route::get('/categories',[CategoryController::class,'index']);




Route::prefix('/services')
->group(function (){
    Route::get('/',[ ServicesController::class,'services'])->middleware('auth:sanctum');
    Route::post('/store',[ServicesController::class,'store'])->middleware('auth:sanctum');
    Route::delete('/delete/{serviceable_id}',[ServicesController::class,'deleteService'])->middleware('auth:sanctum');
    Route::post('/update/{serviceable_id}', [ServicesController::class, 'updateService'])->middleware('auth:sanctum');




    
    Route::post('/request',[ServiceRequestController::class,'storeRequest'])->middleware('auth:customer_api');
    Route::get('customer/requests',[ServiceRequestController::class,'getCustomerRequests'])->middleware('auth:customer_api');
    Route::get('worker/requests',[ServiceRequestController::class,'getWorkerRequests'])->middleware('auth:worker_api');
    Route::get('company/requests',[ServiceRequestController::class,'getCompanyRequests'])->middleware('auth:company_api');
    Route::post('worker/requests/accept',[ServiceRequestController::class,'accept'])->middleware('auth:worker_api');
    Route::get('customer/requests/complete',[ServiceRequestController::class,'complete'])->middleware('auth:customer_api');
    Route::post('worker/requests/cancel',[ServiceRequestController::class,'cancel'])->middleware('auth:worker_api');
    Route::get('customer/requests/cancel',[ServiceRequestController::class,'cancel'])->middleware('auth:customer_api');

    Route::Post('/worker/request/add',[WorkerRequestsController::class,'storeWorkerRequest'])->middleware('auth:worker_api');
    Route::get('/company/request/show',[WorkerRequestsController::class,'fetchCompanyRequests'])->middleware('auth:company_api');
    Route::get('/worker/request/show',[WorkerRequestsController::class,'fetchWorkerRequests'])->middleware('auth:worker_api');
//Route::post('company/requests/accept',[WorkerRequestsController::class,'accept'])->middleware('auth:sanctum');
    Route::post('company/requests/cancel',[WorkerRequestsController::class,'cancel'])->middleware('auth:company_api');
    Route::post('worker/requests/cancelTask',[WorkerRequestsController::class,'taskCancle'])->middleware('auth:worker_api');
    Route::post('worker/requests/taskAccept',[WorkerRequestsController::class,'taskAccept'])->middleware('auth:worker_api');
    Route::post('worker/requests/taskComplet',[WorkerRequestsController::class,'taskComplet'])->middleware('auth:worker_api');



   Route::get('worker/time',[ServiceRequestController::class,'schedulingRequest'])->middleware('auth:worker_api');


   Route::post('company/requests/acceptRequest',[WorkerRequestsController::class,'acceptRequest'])->middleware('auth:sanctum');
   Route::get('company/requests/getEmployee',[WorkerRequestsController::class,'fetchCompanyEmployee'])->middleware('auth:company_api');
   Route::get('company/requests/addTask',[WorkerRequestsController::class,'storeTask'])->middleware('auth:company_api');
   Route::get('worker/requests/showTask',[WorkerRequestsController::class,'getWorkerTaskes'])->middleware('auth:worker_api');

Route::get('freeTime/{serviceable_id}',[ServiceRequestController::class,'calculateAvailableTimes'])->middleware('auth:sanctum');
   Route::get('/getAllWorker',[ProfileController::class,'workerFetch']);

    Route::delete('/delete/images/{imageId}',[ServicesController::class,'deleteImage'])->middleware('auth:sanctum');

});