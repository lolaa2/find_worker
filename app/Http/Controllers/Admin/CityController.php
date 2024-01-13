<?php

namespace App\Http\Controllers\Admin;
use App\DataTables\CitiesDataTable;
use App\Models\User;
use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index(CitiesDataTable $citiesDataTable){
        return $citiesDataTable->render('cities.index');
    }
    


    public function update(City $city,Request $request){

        $request->validate([
            'name' => ['required','string'],
        
        ]);
     
        $city->update([
            'name'=>$request->name,
        ]);
     
        return back()->with('success','City Updated Successfully');
     
     }
     public function edit(City $city){
        
        return view('cities.edit',[
            'city'=>$city,
          
            
        ]);
     }





public function delete(City $city){
    if(User::where('city_id',$city->id)->exists()){
      return back()->with('warning','There are useres related to this type');
    }else{
    $city->delete();
    return back();
    }


}
  public function add( ){
    return view ("cities.add");
  }

  public function addCity(Request $request){
    $request->validate([
        'name'=>['required','string']        
    ]);
    $city=City::create([
        'name'=>$request->name
    ]);
    return back()->with('success','New City Added Succsessfully');

    // $validator = Validator::make($request->all(),[

    // ]);

    // if($validator->fails()){
    //     return back()->withErrors()->withInput();
    // }
  }






}
