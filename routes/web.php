<?php
use  App\Http\Controllers\Admin\PrevioussController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Works_TypeController;
use App\Http\Controllers\Admin\CityController;


use App\Http\Controllers\Admin\WorkersController;
use App\Http\Controllers\Api\PreviousController;
use App\Http\Controllers\CitiesController;
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
    Route::get('/',[DashboardController::class,"index"])->name("dashboard.index");
    Route::prefix('services')-> controller(ServiceController::class)->group(function(){
    Route::get('/',"index")->name("dashboard.services.index");
    Route::get('/create',"create")->name("dashboard.services.create");
    Route::get('/{id}/edit',"edit")->name("dashboard.services.edit");
    Route::get('/{service}/show',"show")->name("dashboard.services.show");
    Route::delete('/{service}/delete',"delete")->name('dashboard.services.delete');

});

    Route::get('/workers',[WorkersController::class,"index"])->name("dashboard.workers.index");
    Route::get('/workers/create',[WorkersController::class,"create"])->name("dashboard.workers.create");
    Route::get('/workers/edit',[WorkersController::class,"edit"])->name("dashboard.workers.edit");
    Route::get('/workers/{id}/show',[WorkersController::class,"show"])->name("dashboard.workers.show");
   


    Route::get('/cities',[CityController::class,"index"])->name("dashboard.cities.index");
    Route::get('/cities/create',[CityController::class,"add"])->name("dashboard.cities.create");
    Route::get('/cities/edit',[CityController::class,"edit"])->name("dashboard.cities.edit");
    Route::get('/cities/{id}/show',[CityController::class,"show"])->name("dashboard.cities.show");

    Route::prefix('previous')-> controller(PrevioussController::class)->group(function(){
    Route::get('/',"index")->name("dashboard.previous.index");
    
    Route::get('/create',"create")->name("dashboard.previous.create");
    Route::get('/edit',"edit")->name("dashboard.previous.edit");
    Route::get('/{id}/show',"show")->name("dashboard.previous.show");
    Route::delete('/{previous}/delete',"delete")->name("dashboard.previous.delete");});

    Route::prefix('workstype')->controller(Works_TypeController::class)->group(function(){

   
    Route::get('/',"index")->name("dashboard.workstype.index");
    Route::get('/create',"create")->name("dashboard.workstype.create");
    Route::get('/edit',"edit")->name("dashboard.workstype.edit");
    Route::get('/{id}/show',"show")->name("dashboard.workstype.show");
    Route::delete('/{id}/delete',"delete")->name("dashboard.workstype.delete");
});
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
