<?php

namespace App\Http\Controllers\Admin;
use App\DataTables\CitiesDataTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(CitiesDataTable $citiesDataTable){
        return $citiesDataTable->render('cities.index');
    }
}
