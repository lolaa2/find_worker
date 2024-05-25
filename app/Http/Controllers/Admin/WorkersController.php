<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WorkersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Work;
use App\Models\Worker;
use App\Models\Service;

class WorkersController extends Controller
{
    public function index(WorkersDataTable $workersDataTable){
        return $workersDataTable->render('workers.index');
    }
    public function create(Service $service){
        return view("workers.create",[
           'service'=> $service,
            'cities'=>City::all(),
            'works'=>Work::all()
        ]);
    }


    public function addWorker(Request $request){

        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'city_id' => ['required','exists:cities,id'],
            'work_id' => ['required','exists:cities,id'],
            'password'=>['required','string']
        ]);
        $worker=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'work_id' => $request->work_id,
            'password'=>$request->password
        ]);
        return back()->with('success','New Worker Added Succsessfully');
     
     
      }

    public function update(User $worker,Request $request){

        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'city_id' => ['required','exists:cities,id'],
            'work_id' => ['required','exists:cities,id']
        ]);

        $worker->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'work_id' => $request->work_id
        ]);

        return back()->with('success','Worker Updated Successfully');

    }
    public function edit(User $worker){
 
        return view('workers.edit',[
            'worker'=>$worker ,
            'cities' => City::all(),
            'works' => Work::all(),
            
        ]);
    }

    public function show(Worker $worker){
        return view("workers.show",[
            'worker'=>$worker
        ]);

    }
    public function delete(User $worker){


       
      if($worker->has('services') ){
        //delete  services 

        $services = $worker->services;

        foreach($services as $service)
        {
          
            if($service->has('images')){
                $service->images()->delete();
            }
          
        }

        $worker->services()->delete();

        
      }

      if($worker->has('previous')){
        //delete previous work 
        $worker->previous()->delete();
      
      }

      //delete worker
      $worker->delete();
      return back()->with('success','Worker deleted successfuly');
}
}