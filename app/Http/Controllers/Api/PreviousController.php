<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\Previous;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PreviousResource;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class PreviousController extends Controller
{
  public function addPrevious(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'description' => ['required', 'string', 'min:3', 'max:1000'],
      'title' => ['required', 'string', 'min:5', 'max:50'],
      'images' => ['nullable', 'array', 'max:5'],
      'images.*' => ['image', 'max:1024']
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => 'Validaion error',
        'errors' => $validator->errors()
      ], 422);
    }


    $work = Previous::create([
      "title" => $request->title,
      "description" => $request->description,
      "user_id" => Auth::id(),
      // "user_name"=>Auth::name()

    ]);
    if (is_array($request->images)) {
      $path = '/images';
      foreach ($request->images as  $image) {
        $name = uniqid('img_') . '.' . $image->getClientOriginalExtension();
        $image->storeAs("/public" . $path, $name);
        $work->images()->create(

          [
            'path' => "/storage" . $path . '/' . $name
          ]
        );
      }
    }
    return response()->json(['data' => $work]);
  }

  public  function deletePrevious(Request $previous, $previousId)
  {

    $user = Auth::id();

    $previous = Previous::where('id', $previousId)
      ->where('user_id', $user)
      ->first();

    if (!$previous) {
      return response()->json([
        'status' => false,
        'message' => 'this previous not found ',
      ], 404);
    }

    $previous->images()->delete();

    $previous->delete();


    return response()->json([
      'status' => true,
      'message' => 'previous deleted successfully.',
    ]);
  }



  public function getUserPrevious(Request $service, $id = NULL)
  {


    if (!is_null($id)) {

      $user = $id;
    } {
      $user = Auth::id();
    }

    $previous = Previous::where('user_id', $user)->with(['images'])->get();
    $previous_res = PreviousResource::collection($previous);
    return response()->json(['data' => $previous_res]);
  }
  public function showAllPrevious(Request $request, $previousId)
  {

    $user = Auth::id();
  }
  public function updatePrevious(Request $request, $previousId)
  { {

      $user = Auth::id();
      $previous = previous::where('id', $previousId)
        ->where('user_id', $user)
        ->first();

      if (!$previous) {
        return response()->json([
          'status' => false,
          'message' => 'Service not found ',
        ], 404);
      }
      $validator = Validator::make($request->all(), [
        'description' => ['required', 'string', 'min:3', 'max:1000'],
        'title' => ['required', 'string', 'min:5', 'max:50'],
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

      $prev_images = $previous->images;



      $count_uploaded_images = count($uploaded_images);

      $count_deleted_images = count($deleted_images);

      $count_images = $prev_images->count();


      if ($count_images + $count_uploaded_images - $count_deleted_images > 5) {
        return response()->json([
          'status' => false,
          'message' => 'Validaion error',
          'errors' => [
            'images' => ['Work Can not has more than 5 images']
          ]
        ], 422);
      }


      if ($count_deleted_images > 0) {
        $previous->images()->whereIn('id', $deleted_images)->delete();
      }


      if ($count_uploaded_images > 0) {
        $path = '/images';
        foreach ($request->images as  $image) {
          $name = uniqid('img_') . '.' . $image->getClientOriginalExtension();
          $image->storeAs("/public" . $path, $name);
          $previous->images()->create(

            [
              'path' => "/storage" . $path . '/' . $name
            ]
          );
        }
      }


      $previous->update(['title' => $request->title, 'description' => $request->description]);
      return response()->json([
        'status' => true,
        'message' => 'previous updated successfully.',
      ]);
    }
  }
}
