<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\table;
use App\Http\Resources\Api\ServiceRequestResource;
use App\Models\ServiceRequest;
use App\DataTables\ServicesRequestDataTable;
use App\DataTables\CustomersDataTable;
use App\DataTables\ServicesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class ServiceController extends Controller
{
    public function index(ServicesDataTable $servicesDataTable){
       return $servicesDataTable->render('services.index');
    }
    public function showServicesReq(ServicesRequestDataTable $servicesRequestDataTable){
        return $servicesRequestDataTable->render('services.showRequest');
     }


     public function showCustomers(Customer $customer,Request $request)
     {
        $customer=Auth::user('customer');
         $requests = ServiceRequest::join('services' , 'services_requests.service_id' , 'services.id')
                                 ->where('services_requests.customer_id' , $customer->id)
                                 ->select(['services_requests.*' , 'services.name'])
                        ;

       return $customer;
         
     }

    public function create(Service $service){
        return view("services.create",[
            'service'=>$service,
            'cities'=> City::all(),
            'categories'=>Category::all(),
            'users'=>User::all()
        ]); 
        
    }

    public function addService( Request $request){
        $request->validate([
            'name' => ['required','string'],
            'description' => ['required','string'],
            'user_id'=>['required','exists:users,id'],
            'price' => ['required','numeric'],
            'category_id' => ['required','exists:categories,id'],
            'city_id' => ['required','exists:cities,id'],
            'new_images' => ['nullable','array'],
            'new_images.*' => ['image'],
            'deleted_images' => ['nullable','array'],
            'deleted_images.*' => ['numeric','exists:images,id']      
        ]);
      
      
        $service=Service::create([
           
            'name'=>$request->name,
            'description'=>$request->description,
            'price' => $request->price,
            'city_id' => $request->city_id,
            'category_id' => $request->category_id,
            "user_id" =>$request->user_id
        ]);
        
        return back()->with('success','New Sercice Added Succsessfully');

    }


    public function update(Service $service,Request $request){

        $request->validate([
            'name' => ['required','string'],
            'description' => ['required','string'],
            'price' => ['required','numeric'],
            'city_id' => ['required','exists:cities,id'],
            'new_images' => ['nullable','array'],
            'new_images.*' => ['image'],
            'deleted_images' => ['nullable','array'],
            'deleted_images.*' => ['numeric','exists:images,id']
        ]);

        $service->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'price' => $request->price,
            'city_id' => $request->city_id
        ]);


        if(is_array($request->new_images)){

            $images = $request->new_images;
    
           
            foreach($images as $image){
    
            
               $name = uniqid('img_').'.'.$image->getClientOriginalExtension();
    
                $path = "/images";
    
                $image->storeAs("/public".$path,$name);
                
                $path="storage".$path."/".$name;
    
                $service->images()->create([
                    'path' => $path
                ]);
    
            }
          }

          if(is_array($request->deleted_images))
          $service->images()->whereIn('id',$request->deleted_images)->delete();

        return back()->with('success','Service Updated Successfully');

    }
    public function edit(Service $service){


        $service->load('images');

        return view("services.edit",[
            'service'=>$service,
            'cities' => City::all(),
            'works' => Work::all(),
    
            
        ]);
    }

    public function show(Service $service , ServicesRequestDataTable $datatable){
        
        return $datatable->render("services.show",[
            'service' => $service
        ]);
    }
   
    public function delete(Service $service){

        if(User::where('id',$service->id)->exists()){
            return back()->with('warning','There are useres related to this type');

        }else{
           $service->requests()->delete();
        $service->images()->delete();
        $service->delete();
        return back()->with('success','Services deleted successfuly');
    }}
}
