<?php

namespace App\Http\Controllers\Api;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function companyFetch(){
        $worker = auth('sanctum')->id();
        $companies = Company::leftjoin(DB::raw( '( select * from worker_requests where worker_id='.$worker.' ) as worker_requests ',)
         , 'worker_requests.company_id' , 'companies.id')->select('companies.*')->whereNull('worker_id')->with('category') ->with('services')->get();
       
        $company = CompanyResource::collection($companies);
        
        return response()->json([
            'data' => $company
        ]);
    }

public function mycompaniesFetch(){
    $worker = auth('sanctum')->id();
    $companies = Company::leftJoin('worker_requests', 'companies.id', '=', 'worker_requests.company_id')
                         ->where('worker_requests.company_id', null)
                         ->orWhere('worker_requests.worker_id', '<>', $worker)
                        ->select('companies.*')
                         ->with('category')
                         ->get();
    
    $company = CompanyResource::collection($companies);
    
    return response()->json([
        'data' => $company
    ]);
}


public function companiesFetch(Request $request){
    
    $companies = Company::whereId($request->id)
    ->get();

    
    $company = CompanyResource::collection($companies);
    
    return response()->json([
        'data' => $company
    ]);
}

}