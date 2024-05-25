<?php

namespace App\Http\Controllers\Admin;
use App\DataTables\CompanyDataTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\City;
use App\Models\Service;

class CompanyController extends Controller
{


    public function index(CompanyDataTable $companyDataTable){
        return $companyDataTable->render('companies.index');
    }


    
public function create(Service $service){
    return view("companies.create",[
       'service' =>$service,
       'cities'=>City::all()
    ]);
}
    
    public function add (Request $request){
        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'password'=>['required','string'],
            'city_id' => ['required','exists:cities,id'],
            
        ]);
        $company=Company::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'password'=>$request->password,
            'city_id' => $request->city_id
        ]);
        return back()->with('success','New Company Added Succsessfully');
     
      }



      public function show(Company $company){
        
        return view("companies.show",[
            'company' => $company
        ]);
    }
    public function edit(Company $company){
 
        return view('companies.edit',[
            'company'=>$company ,
            'cities' => City::all()
            
        ]);
    }
  

    public function update(Company $company,Request $request){

        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'city_id' => ['required','exists:cities,id'],
            // 'work_id' => ['required','exists:cities,id']
        ]);

        $company->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            // 'work_id' => $request->work_id
        ]);

        return back()->with('success','Company Updated Successfully');

    }
public function delete(Company $company){
    $company->delete();
      return back()->with('success','Company deleted successfuly');
}


}
