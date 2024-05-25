<?php

namespace App\Http\Controllers\Admin;
use App\DataTables\CustomersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    
    public function index(CustomersDataTable $customerDataTable){
        return $customerDataTable->render('customers.index');
    }
    
     public function create(Service $service){
            return view("customers.create",[
                'service'=>$service,
                'cities'=> City::all(),
            ]);
        }
    public function addCustomer(Request $request){
        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'password'=>['required','string'],
            'city_id'=>['required','exists:cities,id']
        ]);
        $company=Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'password'=>$request->password,
            'city_id' => $request->city_id
        ]);
        return back()->with('success','New Customer Added Succsessfully');
     
      }



      public function show(Customer $customer){
        
        return view("companies.show",[
            'company' => $customer
        ]);
    }
    public function edit(Customer $customer){
 
        return view('customers.edit',[
            'customer'=>$customer ,
           'cities' => City::all()
            
        ]);
    }
  

    public function update(Customer $customer,Request $request){

        $request->validate([
            'name' => ['required','string'],
            'email' => ['required','string'],
            'phone' => ['required','numeric'],
            'city_id' => ['required','exists:cities,id'],
        ]);

        $customer->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone' => $request->phone,
            'city_id' => $request->city_id,
        ]);

        return back()->with('success','Customer Updated Successfully');

    }




    public function delete(ServiceRequest $serviceRequest, Customer $customer){

        try {
           
                $customer->delete();
                return back()->with('success', 'Customer deleted successfully');
         
        } catch (\Exception $e) {
            return back()->with('error', "There are related records in services_requests table");
        }
    }


}
