<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkerRequestResource;
use App\Models\WorkerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class WorkerRequestsController extends Controller
{
    public function fetchWorkerRequests(Request $request)
    {
        $company = Auth::guard('company_api')->user();
        $requests = WorkerRequest::join('users', 'worker_requests.worker_id', 'users.id')
        ->with('worker')
            ->where('worker_requests.company_id', $company->id)
            ->select(['worker_requests.*', 'users.name'])
            ->paginate(5);
        $worker_req = WorkerRequestResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $worker_req ,
        ]);
    }


    public function storeWorkerRequest(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'note' => ['required', 'string'],
            'company_id' => ['required', 'integer'],
            'skils'=>['required', 'string'],
            

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaion error',
                'errors' => $validator->errors()
            ], 422);
        }

        $workerRequest = WorkerRequest::create([
            'company_id' => $request->company_id,
            'worker_id' => Auth::guard('worker_api')->id(),
            'note' => $request->note,
            'skils'=>$request->skils
          
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Request added successfully',
            'data' => $workerRequest
        ]);
    }
}







