<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function llal(){
        $works=Work ::all() ;
        return response()->json( [
            'data'=>$works]);

    }
}
