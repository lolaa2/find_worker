<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){

        $customersStat  = $this->getCustomerStat();

        return view('index' , [
            'customersStat' => $customersStat
        ]);
    }

    private function getCustomerStat()
    {

        $customers = Customer::where('created_at' , '>=' , now()->startOfYear())
                    
        ->selectRaw('count(*) as ct , month(created_at) as m')            
        ->groupBy('m')
                    ->get()->keyBy('m')->toArray();

        $data = [];

        for($i=1 ; $i<13 ; $i++){
            $data[$i] = key_exists($i , $customers) ? $customers[$i]['ct'] : 0;
        }

        return $data;

    }
}
