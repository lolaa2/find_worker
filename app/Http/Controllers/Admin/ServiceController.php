<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ServicesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(ServicesDataTable $servicesDataTable){
       return $servicesDataTable->render('services.index');
    }
    public function create(){
        return view("services.create");
    }

    public function edit(){
        return view("services.edit");
    }

    public function show(Service $service){
        
        return view("services.show",[
            'service' => $service
        ]);
    }
    public function delete(Service $service){
        $service->images()->delete();
        $service->delete();
        return back()->with('success','Services deleted successfuly');
    }
}
