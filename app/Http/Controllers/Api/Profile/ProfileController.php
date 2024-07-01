<?php

namespace App\Http\Controllers\Api\Profile;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function getUserProfile (){
        return response()->json([
            'data' => Auth::user()
        ]);
        
    }
    public function workerFetch(){
        $worker = User::all();
        return response()->json([
            'data' => $worker
        ]);
    }
}
