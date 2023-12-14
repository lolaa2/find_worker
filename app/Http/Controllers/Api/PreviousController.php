<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Models\Previous;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PreviousResource;

class PreviousController extends Controller
{
    public function addPrevious (Request $request){
        $validator = Validator::make($request->all(),[
            'description' => ['required','string','min:3','max:1000'],
            'title'=>['required','string','min:5','max:50'],
        ]);
        
        if($validator->fails()){
            return response()->json([
              'status' => false,
              'message' => 'Validaion error',
              'errors' => $validator->errors()
          ],422);
          }
    
        
          $service =Previous::create ([
            "title"=>$request->title,
            "description"=>$request->description,
            "user_id" =>Auth::id()
            ]);
     }
     public function getUserPrevious(Request $service ){
     
        $user =Auth::id();
        $previous = Previous::where('user_id',$user)->with(['title','describtion'])->get();
          $previous_res = PreviousResource::collection($previous);
        return response()->json([ 'data' => $previous_res]);
  
  }
}
