<?php

namespace App\Http\Controllers\Api;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function companyFetch(){
        $companies = Company::with('city')->get();
        $company = CompanyResource::collection($companies);
        
        return response()->json([
            'data' => $company
        ]);
    }
}
