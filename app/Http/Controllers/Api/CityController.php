<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index(){
        $cities = City::all();
        return response()->json([
            'data'=>$cities
        ]);
    }
}
