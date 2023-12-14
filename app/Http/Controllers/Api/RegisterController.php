<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => ['required','string','min:2','max:200'],
            'email' => ['required','string','email','unique:users,email'],
            'phone' => ['required','string','unique:users,phone'],
            'password' => ['required','string'],
            'confirm_password' => ['required','string','same:password'],
            'city_id' => ['required','numeric','exists:cities,id'],
            'work_id' => ['required','numeric','exists:works,id'],
            
        ]); 


        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validaion error',
                'errors' => $validator->errors()
            ],422);
        }


        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'name' => $request->name,
            'work_id' => $request->work_id
        ]);

        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'data'=>[
                'status' => true,
                'token' => $token,
                'code' => random_int(1111,9999)
            ]
        ]);
    }
}
