<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\company;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request, $type = NULL)
    {
        switch ($type) {
            case 'customer':
                return $this->customerRegister($request);
            case 'worker':
                return  $this->workerRegister($request);
            case "company":
                return $this->companyRegister($request);
            default:
                return abort(404);
        }
    }


    private function workerRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'string', 'same:password'],
            'city_id' => ['required', 'numeric', 'exists:cities,id'],
            'work_id' => ['required', 'numeric', 'exists:works,id'],

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaion error',
                'errors' => $validator->errors()
            ], 422);
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
            'data' => [
                'status' => true,
                'token' => $token,
                'type' => 'worker',
                'code' => random_int(1111, 9999)
            ]
        ]);
    }


    private function customerRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'email' => ['required', 'string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'string', 'unique:customers,phone'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'string', 'same:password'],
            'city_id' => ['required', 'numeric', 'exists:cities,id'],

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaion error',

                'errors' => $validator->errors()
            ], 422);
        }


        $customer = Customer::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'name' => $request->name
        ]);

        $token = $customer->createToken('api_token')->plainTextToken;
        return response()->json([
            'data' => [
                'status' => true,
                'token' => $token,
                'type' => 'customer',
                'code' => random_int(1111, 9999)
            ]
        ]);
    }



    private function companyRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'email' => ['required', 'string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'string', 'unique:customers,phone'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'string', 'same:password'],
            'city_id' => ['required', 'numeric', 'exists:cities,id'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaion error',
                'errors' => $validator->errors()
            ], 422);
        }


        $company = Company::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'category_id' => $request->category_id,
            'name' => $request->name
        ]);

        $token = $company->createToken('api_token')->plainTextToken;
        return response()->json([
            'data' => [
                'status' => true,
                'token' => $token,
                'type' => 'company',
                'code' => random_int(1111, 9999)
            ]
        ]);
    }
}
