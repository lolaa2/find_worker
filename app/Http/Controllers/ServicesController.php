<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServicesResource;
use App\Models\Company;
use App\Models\Image;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Avg;
class ServicesController extends Controller
{
  public function services(Request $request)
  {

    $services = Service::with(['serviceable', 'city', 'category', 'images'])
      ->when($request->city_id, function ($query) use ($request) {
        $query->where('city_id', $request->city_id);
      })
      ->when($request->min_price, function ($query) use ($request) {
        $query->where('price', ">=", $request->min_price);
      })
      ->when($request->max_price, function ($query) use ($request) {
        $query->where('price', "<=", $request->max_price);
      })

      ->when($request->category_id, function ($query) use ($request) {
        $query->where('category_id', $request->category_id);
      })
      ->when($request->user_id , function ($query) use ($request) {
        $query->where('serviceable_type', User::class)->where('serviceable_id',$request->user_id);
      })
      ->when($request->company_id , function ($query) use ($request) {
        $query->where('serviceable_type', Company::class)->where('serviceable_id',$request->company_id);
      })
      ->latest()
      ->withAvg('requests','rate')
      ->paginate(perPage: $request->per_page ?? 5);
    $services_res = ServicesResource::collection($services);
    return response()->json([
      'lastPage' => $services->lastPage(),
      'currentPage' => $services->currentPage(),
      'data' => $services_res,
    ]);
  }
  public function getUserServices(Request $request)
  {
      
    $userType = User::class;

    if(Auth::guard('company_api')->check())
    {
      $userType =  Company::class;
    }


    $services = Service::where('serviceable_type', $userType)->with(['serviceable', 'city', 'category', 'images'])->get();

    $services_res = ServicesResource::collection($services);
    
    return response()->json(['data' => $services_res]);
   
  }
  public function deleteService(Request $request, $serviceId)
  {

   
    $userType = User::class;

    if(Auth::guard('company_api')->check())
    {
      $userType =  Company::class;
    }



    // التحقق مما إذا كان المستخدم يمتلك الخدمة قبل حذفها
    $service = Service::where('id', $serviceId)
      ->where('serviceable_type', $userType)
      ->first();

    if (!$service) {
      return response()->json([
        'status' => false,
        'message' => 'Service not found ',
      ], 404);
    }

    $service->images()->delete();
    $service->requests()->delete();
    //الخدمة موجودة 

    $service->delete();


    return response()->json([
      'status' => true,
      'message' => 'Service deleted successfully.',
    ]);
  }
  public function updateService(Request $request, $serviceId)
  { {

       
    $userType = User::class;

    if(Auth::guard('company_api')->check())
    {
      $userType =  Company::class;
    }


      $service = Service::where('serviceable_id', $serviceId)
        ->where('serviceable_type', $userType)
        ->first();

      if (!$service) {
        return response()->json([
          'status' => false,
          'message' => 'Service not found ',
        ], 404);
      }
      $validator = Validator::make($request->all(), [
        'description' => ['required', 'string', 'min:3', 'max:1000'],
        'title' => ['required', 'string', 'min:5', 'max:50'],
        'price'=>['required','numeric'],
        'images' => ['nullable', 'array', 'max:5'],
        'images.*' => ['image', 'max:1024'],
        'deleted_images' => ['nullable', 'array',],
        'deleted_images.*' => ['numeric', 'exists:images,id',]

      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validaion error',
          'errors' => $validator->errors()
        ], 422);
      }
      $uploaded_images = $request->images ?? [];

      $deleted_images = $request->deleted_images ?? [];

      $ser_images = $service->images;



      $count_uploaded_images = count($uploaded_images);

      $count_deleted_images = count($deleted_images);

      $count_images = $ser_images->count();


      if ($count_images + $count_uploaded_images - $count_deleted_images > 5) {
        return response()->json([
          'status' => false,
          'message' => 'Validaion error',
          'errors' => [
            'images' => ['Serves Can not has more than 5 images']
          ]
        ], 422);
      }


      if ($count_deleted_images > 0) {
        $service->images()->whereIn('id', $deleted_images)->delete();
      }


      if ($count_uploaded_images > 0) {
        $path = '/images';
        foreach ($request->images as  $image) {
          $name = uniqid('img_') . '.' . $image->getClientOriginalExtension();
          $image->storeAs("/public" . $path, $name);
          $service->images()->create(

            [
              'path' => "/storage" . $path . '/' . $name
            ]
          );
        }
      }

   

      $service->update([
         'description' => $request->description,
        'name' => $request->title, 'price' => $request->price, 'category_id' => $request->category_id, 'city_id' => $request->city_id
      ]);
      return response()->json([
        'status' => true,
        'message' => 'services updated successfully.',
      ]);
    }
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'min:3', 'max:200'],
      'description' => ['required', 'string', 'min:3', 'max:1000'],
      'category_id' => ['required', 'numeric', 'exists:categories,id'],
      'city_id' => ['required', 'numeric', 'exists:cities,id'],
      'price' => ['required', 'numeric', 'min:0'],
      'images' => ['nullable', 'array', 'max:5'],
      'images.*' => ['image', 'max:10240']
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => 'Validaion error',
        'errors' => $validator->errors()
      ], 422);
    }


    if (is_array($request->images)) {

      $images = $request->images;

      $paths = [];
      foreach ($images as $image) {


        $name = uniqid('img_') . '.' . $image->getClientOriginalExtension();

        $path = "/images";

        $image->storeAs("/public" . $path, $name);

        $paths[] = "storage" . $path . "/" . $name;
      }
    }

    $userType = User::class;

    if(Auth::guard('company_api')->check())
    {
      $userType =  Company::class;
    }

    $service = Service::create([
      "name" => $request->name,
      "description" => $request->description,
      "category_id" => $request->category_id,
      "city_id" => $request->city_id,
      "price" => $request->price,
      "serviceable_id" => Auth::id(),
      "serviceable_type" => $userType,
    ]);

    foreach ($paths as $image_path) {
      $service->images()->create([
        'path' => $image_path
      ]);
    }

    return response()->json([
      'status' => true,
      'message' => 'Service created successfully',
      'data' => $service
    ]);
  }

  public function deleteImage($imageId)
  {
    
    $userType = User::class;

    if(Auth::guard('company_api')->check())
    {
      $userType =  Company::class;
    }


    $image = Image::where('id', $imageId)
      ->whereHasMorph('imageable', Service::class, function ($query) use ($userType) {
        $query->where('serviceable_type', $userType);
      })->first();

    if ($image) {

      $image->delete();
      return response()->json(['message' => 'The photo Deleted Successfully']);
    } else {
      return response()->json(['message' => ' No Image found ']);
    }
  }
}
