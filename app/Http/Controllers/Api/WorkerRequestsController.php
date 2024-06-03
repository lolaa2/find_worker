<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkerRequestResource;
use App\Models\WorkerRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class WorkerRequestsController extends Controller
{
    public function fetchCompanyRequests(Request $request)
    {
        $company = Auth::guard('company_api')->user();
        $requests = WorkerRequest::join('users','worker_requests.worker_id', 'users.id')
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


    public function accept(Request $request)
    {
        $workerRequest = WorkerRequest::where('worker_id', $request->worker_id)
        ->where('id', $request->request_id)
        ->firstOrFail();
        if($workerRequest->status === 'pending')
        {
            $workerRequest->status = 'accepted';
            $workerRequest->save();

            return response()->json([
                'message' => 'Request accepted successfully  !'
            ]);
        }

        return response()->json([
            'message' => 'Request must be pending !'
        ],403);
    }

    public function cancel(Request $request){
   $workerRequest = WorkerRequest::whereWorkerId($request->worker_id)
                            ->whereId($request->request_id)
                            ->firstOrFail();

if($workerRequest->status == 'pending')
{
$workerRequest->status = 'rejected';
$workerRequest->save();

return response()->json([
'message' => 'Request canceled successfully  !'
]);
}

return response()->json([
'message' => 'Request must be pending !'
],403);
    }

    // public function fetchWorkerRequests(Request $request)
    // {
    //     $company = Auth::guard('worker_api')->user();
    //     $requests = WorkerRequest::join('companies','worker_requests.company_id', 'users.id')
    //    ->with($company)
    //         ->where('worker_requests.company_id', $company->id)
    //         ->select(['worker_requests.*', 'users.name'])
    //         ->paginate(5);
    //     $worker_req = WorkerRequestResource::collection($requests);
    //     return response()->json([
    //         'lastPage' => $requests->lastPage(),
    //         'currentPage' => $requests->currentPage(),
    //         'data' => $worker_req ,
    //     ]);
    // }  
    public function fetchWorkerRequests(Request $request)
    {
        $worker = Auth::guard('worker_api')->user();
        $requests = WorkerRequest::join('companies', 'worker_requests.company_id', 'companies.id')
        ->where('worker_requests.worker_id', $worker->id)
        ->select(['worker_requests.*', 'companies.name as company_name'])
        ->paginate(5);

        $worker_req = WorkerRequestResource::collection($requests);
    
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $worker_req,
        ]);
    }


    public function addWorker(Request $request)
    {
        $worker = Auth::guard('worker_api')->user();
        $status = Request::where('worker_id', $worker->status);

        if ($status === 'accepted') {
            $workerinfor = User::where('worker_id', $worker)->first();
            return response()->json($workerinfor, 200);
        } else {
            return response()->json(['message' => 'Request is not accepted'], 404);
        }
    }
    


    
}







