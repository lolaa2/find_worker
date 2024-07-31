<?php

namespace App\Http\Controllers\Api\Profile;
use App\Models\User;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function getUserProfile (){
        $worker = Auth::user();
        return new UserResource($worker);
    }
    public function workerFetch(){
        $worker = User::all();
        $workers=UserResource::collection($worker);
        return response()->json([
            'data' => $workers
        ]);
    }

    public function getCompanyProfile (){
        $company = Auth::user();
        return new CompanyResource($company);
    }
    
    public function getCustomerProfile (){
        $customer = Auth::user();
        return response()->json([
            'data' => $customer
        ]);
}
}