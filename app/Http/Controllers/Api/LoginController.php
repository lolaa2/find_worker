<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(  Request $request , $type=NULL   )
    {
      
        //استخدمنا request لجلب البيانات المرسلة من الفرونت 

        //1) نتحقق من البيانات 

        $validator = Validator::make($request->all(),[
            'phone' => ['required','string','exists:'.$this->getTypeTable($type).',phone'],
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

        $guard = $this->getGuardFromType($type);



        if(Auth::guard($guard)->attempt(['password'=>$request->password,'phone'=>$request->phone])){
            $user = Auth::guard($guard)->user();
            
            return response()->json([
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->phone,
                'type' => $type,
                'token' => $user->createToken('api_token')->plainTextToken
            ]);
        }else{
            //اعادة خطأ في حال كلمة المرور غير صحيحية
            return response()->json([
                'message' => 'Wrong password',
            ],401);
        }

    }

    private function getGuardFromType($type)
    {
        switch($type)
        {
            case 'customer' :
                return 'customer';
            case 'worker':
                return 'web';
            case 'company' :
                return 'company';
            default :
                abort(403);
        }
    }

    private function getTypeTable($type)
    {
        switch($type)
        {
            case 'customer' :
                return 'customers';
            case 'worker':
                return 'users';
            case 'company' :
                return 'companies';
            default :
                abort(403);
        }
    }
}
