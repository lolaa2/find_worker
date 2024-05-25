<?php

use App\Http\Controllers\Admin\CategoreisController;
use  App\Http\Controllers\Admin\PrevioussController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Works_TypeController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\WorkersController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Api\PreviousController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/services',function (){
    return "Hello World!";
});

Route::post('/services',function (){
    return 'Post Request';
}); 


Route::get('cities',function (){
    return 'Cities';
});

     Route::prefix('dashboard')->group(function(){
        Route::prefix('login')->controller((LoginController::class))->group(function(){
            Route::get('/','index');
            Route::post('/','login')->name('login');
            
        });
        Route::post('/logout',[LogoutController::class,'logout'])->name('logout');
            

    Route::middleware('admin')->group(function (){
    
    Route::get('/',[DashboardController::class,"index"])->name("dashboard.index");
    Route::prefix('services')-> controller(ServiceController::class)->group(function(){
    Route::get('/',"index")->name("dashboard.services.index");
    Route::get('/create',"create")->name("dashboard.services.create");
    Route::post('/add',"addService")->name("dashboard.services.add");
    Route::get('/{service}/edit',"edit")->name("dashboard.services.edit");
    Route::put('/{service}/update',"update")->name('dashboard.services.update');
    Route::get('/{service}/show',"show")->name("dashboard.services.show");
    Route::delete('/{service}/delete',"delete")->name('dashboard.services.delete');
    Route::get('/showRequest',"showServicesReq")->name("dashboard.servicesRequest.showRequest");
    Route::get('/{service}/show/customer',"showCustomers")->name("dashboard.showCustomers.showCustomer");




});
    Route::prefix('workers')-> controller(WorkersController::class)->group(function(){

    Route::get('',"index")->name("dashboard.workers.index");
    Route::get('/create',"create")->name("dashboard.workers.create");
    Route::post('/add',"addWorker")->name("dashboard.workers.add");
    Route::get('/{worker}/edit',"edit")->name("dashboard.workers.edit");
    Route::get('/{worker}/show',"show")->name("dashboard.workers.show");
    Route::delete('/{worker}/delete',"delete")->name('dashboard.workers.delete');
    Route::put('/{worker}/update',"update")->name('dashboard.workers.update');
});


Route::prefix('cities')-> controller(CityController::class)->group(function(){

    Route::get('/',"index")->name("dashboard.cities.index");
    Route::get('/create',"add")->name("dashboard.cities.create");
    Route::post('/store',"addCity")->name("dashboard.cities.store");
    Route::get('/{city}/edit',"edit")->name("dashboard.cities.edit");
    Route::delete('/{city}/delete',"delete")->name("dashboard.cities.delete");
    Route::put('/{city}/update',"update")->name("dashboard.cities.update");
});
    Route::prefix('previous')-> controller(PrevioussController::class)->group(function(){

    Route::get('/',"index")->name("dashboard.previous.index");
    Route::get('/create',"create")->name("dashboard.previous.create");
    Route::post('/add',"addPrevious")->name("dashboard.previous.add");
    Route::get('/{previous}/edit',"edit")->name("dashboard.previous.edit");
    Route::get('/{previous}/show',"show")->name("dashboard.previous.show");
    Route::delete('/{previous}/delete',"delete")->name("dashboard.previous.delete");
    Route::put('/{previous}/update',"update")->name("dashboard.previous.update");
});

    Route::prefix('workstype')->controller(Works_TypeController::class)->group(function(){   

    Route::get('/',"index")->name("dashboard.workstype.index");
    Route::get('/create',"create")->name("dashboard.workstype.create");
    Route::post('/add',"addworktype")->name("dashboard.workstype.add");
    Route::get('/{workt}/edit',"edit")->name("dashboard.workstype.edit");
    Route::delete('/{workt}/delete',"delete")->name("dashboard.workstype.delete");
    Route::put('/{workt}/update',"update")->name("dashboard.workstype.update");
});

Route::prefix('categories')->controller(CategoreisController::class)->group(function(){   

    Route::get('/',"index")->name("dashboard.categoreis.index");
    Route::get('/create',"add")->name("dashboard.categoreis.create");
    Route::post('/add',"addcat")->name("dashboard.categoreis.add");
    Route::get('/{cate}/edit',"edit")->name("dashboard.categoreis.edit");
    Route::get('/{cate}/show',"show")->name("dashboard.categoreis.show");
    Route::delete('/{cate}/delete',"delete")->name("dashboard.categoreis.delete");
    Route::put('/{cate}/update',"update")->name("dashboard.categoreis.update");
});
  


Route::prefix('companies')->controller(CompanyController::class)->group(function(){   

    Route::get('/',"index")->name("dashboard.companies.index");
    Route::get('/create',"create")->name("dashboard.companies.create");
    Route::post('/add',"add")->name("dashboard.companies.add");
    Route::get('/{company}/edit',"edit")->name("dashboard.companies.edit");
    Route::get('/{cate}/show',"show")->name("dashboard.companies.show");
    Route::delete('/{company}/delete',"delete")->name("dashboard.companies.delete");
    Route::put('/{company}/update',"update")->name("dashboard.companies.update");
});

Route::prefix('customers')-> controller(CustomerController::class)->group(function(){

    Route::get('',"index")->name("dashboard.customers.index");
    Route::get('/create',"create")->name("dashboard.customers.create");
    Route::post('/add',"addCustomer")->name("dashboard.customers.add");
    Route::get('/{customer}/edit',"edit")->name("dashboard.customers.edit");
    Route::get('/{customer}/show',"show")->name("dashboard.customers.show");
    Route::delete('/{customer}/delete',"delete")->name('dashboard.customers.delete');
    Route::put('/{customer}/update',"update")->name('dashboard.customers.update');
});




});
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
