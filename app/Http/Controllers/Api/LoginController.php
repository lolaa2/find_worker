<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
       
        //استخدمنا request لجلب البيانات المرسلة من الفرونت 

        //1) نتحقق من البيانات 

        $validator = Validator::make($request->all(),[
            'phone' => ['required','string','exists:users,phone'],
            'password' => ['required','string']
        ]);

        //2)اعادة خطا في حال فشل التحقق
        if($validator->fails()){
            return response()->json([
                'message' => 'Validaion error',
                'errors' => $validator->errors()
            ],422);
        }

        //3)التحقق من كلمة المرور


        if(Auth::attempt(['password'=>$request->password,'phone'=>$request->phone])){
            $user = Auth::user();
            return response()->json([
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->phone,
                'token' => $user->createToken('api_token')->plainTextToken
            ]);
        }else{
            //اعادة خطأ في حال كلمة المرور غير صحيحية
            return response()->json([
                'message' => 'Wrong password',
            ],401);
        }

    }
}
