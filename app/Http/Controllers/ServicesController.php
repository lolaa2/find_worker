<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServicesResource;
use Illuminate\Http\Request;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function services(Request $request){
       $services = Service :: with(['user','city','category','images'])
            ->when($request->city_id,function($query) use($request) {
                $query->where('city_id',$request->city_id);
            
            })
            ->when($request->min_price,function($query) use($request) {
              $query->where('price',">=",$request->min_price);
          
          })
              ->latest()
              ->paginate(5);

       $services_res = ServicesResource::collection($services);
      return response()->json([
        'data' => $services_res
      ]);

    }
    public function getUserServices(Request $service ){
     
        $user =Auth::id();
        $services = Service::where('user_id',$user)->with(['user','city','category','images'])->get();
          $services_res = ServicesResource::collection($services);
        return response()->json([ 'data' => $services_res]);
  
  }
  public function deleteService(Request $request, $serviceId)
{
 
    $user = Auth::id();

    // التحقق مما إذا كان المستخدم يمتلك الخدمة قبل حذفها
    $service = Service::where('id', $serviceId)
                      ->where('user_id', $user)
                      ->first();    

                       if (!$service) {
                          return response()->json([
                              'status' => false,
                              'message' => 'Service not found ',
                          ], 404);
                        }     

                      $service->images()->delete();

                      //الخدمة موجودة 

                      $service->delete();


                        return response()->json([
                        'status' => true,
                        'message' => 'Service deleted successfully.',
                    ]);



                  }
                    public function updateService (Request $request , $serviceId){
                      {
                       
                          $user = Auth::id();
                          $service = Service::where('id', $serviceId)
                                          ->where('user_id', $user)
                                          ->first();    
                    
                                           if (!$service) {
                                              return response()->json([
                                                  'status' => false,
                                                  'message' => 'Service not found ',
                                              ], 404);
                                            }     
                                            $validator = Validator::make($request->all(),[
                                              'description' => ['required','string','min:3','max:1000'],
                                              'title'=>['required','string','min:5','max:50'], 
                                              'images'=>['nullable','array','max:5' ],
                                              'images.*'=>['image','max:1024'],
                                              'deleted_images'=>['nullable','array',],
                                              'deleted_images.*'=>['numeric','exists:images,id',]
                                          
                                            ]);
                    
                                            if($validator->fails()){
                                              return response()->json([
                                                'status' => false,
                                                'message' => 'Validaion error',
                                                'errors' => $validator->errors()
                                            ],422);
                                            }
                                            $uploaded_images = $request->images??[];
                    
                                            $deleted_images = $request->deleted_images??[];
                    
                                            $ser_images = $service->images;
                    
                    
                    
                                            $count_uploaded_images = count($uploaded_images);
                    
                                            $count_deleted_images = count($deleted_images);
                    
                                            $count_images = $ser_images->count();
                    
                    
                                            if($count_images+$count_uploaded_images-$count_deleted_images>5){
                                              return response()->json([
                                                'status' => false,
                                                'message' => 'Validaion error',
                                                'errors' => [
                                                  'images'=>['Serves Can not has more than 5 images']
                                                ]
                                            ],422);
                                            }
                    
                    
                                            if($count_deleted_images>0){
                                              $service->images()->whereIn('id',$deleted_images)->delete();
                                            }
                    
                                            
                                            if($count_uploaded_images>0){
                                              $path='/images';
                                              foreach ($request->images as  $image) {
                                              $name= uniqid('img_').'.'.$image->getClientOriginalExtension();
                                              $image ->storeAs("/public".$path,$name);
                                              $service->images()->create(
                                
                                                [
                                                  'path'=>"/storage".$path.'/'.$name
                                                ]
                                              );
                                
                                            }}
                    
                                           
                                            $service->update(['title'=>$request->title,'description'=>$request->description]);
                                            return response()->json([
                                            'status' => true,
                                            'message' => 'previous updated successfully.',  ]);
                    
                       
                    
                    }
                    
   

  
}






    public function store(Request $request)
    {

      $validator = Validator::make($request->all(),[
          'name' => ['required','string','min:3','max:200'],
          'description' => ['required','string','min:3','max:1000'],
          'category_id' => ['required','numeric','exists:categories,id'],
          'city_id' => ['required','numeric','exists:cities,id'],
          'price' => ['required','numeric','min:0'],
          'images'=>['nullable','array','max:5'],
          'images.*'=>['image','max:10240']
      ]);

      if($validator->fails()){
        return response()->json([
          'status' => false,
          'message' => 'Validaion error',
          'errors' => $validator->errors()
      ],422);
      }


      if(is_array($request->images)){

        $images = $request->images;

        $paths=[];
        foreach($images as $image){

        
           $name = uniqid('img_').'.'.$image->getClientOriginalExtension();

            $path = "/images";

            $image->storeAs("/public".$path,$name);
            
            $paths[]="storage".$path."/".$name;


        }
      }

    
      $service =Service::create ([
      "name"=>$request->name,
      "description"=>$request->description,
      "category_id"=>$request->category_id,
      "city_id"=>$request->city_id,
      "price"=>$request->price,
      "user_id" =>Auth::id()
      ]);

      foreach($paths as $image_path){
        $service->images()->create([
            'path' => $image_path
        ]);
       
      }

      return response()->json([
        'status' => true,
        'message' => 'Service created successfully',
        'data' => $service ]);
    


    }
} 
