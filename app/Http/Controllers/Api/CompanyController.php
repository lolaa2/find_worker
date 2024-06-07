<?php

namespace App\Http\Controllers\Api;
use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function companyFetch(){
        $companies=  Company::all();
        return response()->json([
            'data'=>$companies
        ]);
    }
}
