<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WorkerRequestResource;
use App\Models\WorkerRequest;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Task;
use App\Models\Employee;
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
$worker= Auth::guard('worker_api')->user();
        $validator = Validator::make($request->all(), [
            'note' => ['required', 'string'],
            'company_id' => ['required', 'integer'],
            'skils'=>['required', 'string'],
            'worker_name'=>['required', 'string','exists:users,name'],
            'company_name'=>['required', 'string','exists:companies,name'],

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
            'company_name' => $request->company_name,
            'worker_name' =>$worker->name,
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


    // public function addWorker(Request $request)
    // {
    //     $worker = Auth::guard('worker_api')->user();
    //     $status = Request::where('worker_id', $worker->status);

    //     if ($status === 'accepted') {
    //         $workerinfor = User::where('worker_id', $worker)->first();
    //         return response()->json($workerinfor, 200);
    //     } else {
    //         return response()->json(['message' => 'Request is not accepted'], 404);
    //     }
    // }
    public function acceptRequest(Request $request)
{  $company = Auth::guard('company_api')->user();
    $workerRequest = WorkerRequest::where('worker_id', $request->worker_id)
    ->where('worker_name', $request->worker_name)
        ->where('id', $request->request_id)
        ->first();


    if (!$company) {
        return response()->json([
            'status' => false,
            'message' => 'Company not found',
        ], 404);
    }

    if (!$workerRequest) {
        return response()->json([
            'status' => false,
            'message' => 'Request Not Found ',
        ], 404);
        if (!$workerRequest->status=='accepted') {
            return response()->json([
                'status' => false,
                'message' => 'Must Be Accepted',
            ], 404);
        }
    }

    $employee = Employee::create([
        "company_id" => $company->id,
        "company_name" => $company->name,
        "worker_name" => $request->worker_name,
        "worker_id" => $request->worker_id,
        "status" => $request->status,
    ]);
    return response()->json([
        'message' => 'Employee created successfully',
        'data' => $employee
    ]);
}
public function fetchCompanyEmployee(Request $request)
{
    $company = Auth::guard('company_api')->user();
    $requests = WorkerRequest::join('users', 'worker_requests.worker_id','users.id')
        ->join('company_employee', 'worker_requests.worker_id', 'company_employee.worker_id')
        ->where('worker_requests.company_id', $company->id)
        ->select('worker_requests.*', 'users.name')
        ->paginate(5);
    $worker_req = WorkerRequestResource::collection($requests);
    return response()->json([
        'lastPage' => $requests->lastPage(),
        'currentPage' => $requests->currentPage(),
        'data' => $worker_req,
    ]);
}












public function storeTask(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string'],
        'description'=> ['required', 'string'],
        'service_id' => ['required', 'integer'],
        'start_time' => ['required', 'date_format:Y-m-d H:i:s'],
        'end_time' => ['required', 'date_format:Y-m-d H:i:s'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validaion error',
            'errors' => $validator->errors()
        ], 422);
    }

    $requests = Task::where('service_id', $request->service_id)
    ->where('worker_id', $request->worker_id)
    ->where('worker_request_id', $request->worker_request_id)
    ->where(function ($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                 ->orWhereBetween(DB::raw("timestamp('".$request->start_time."')"), [DB::raw('start_time'),DB::raw('end_time')])
                ->orWhereBetween(DB::raw("timestamp('".$request->end_time."')"), [DB::raw('start_time'),DB::raw('end_time')]);
        })
        ->where('status', 'accepted') 
        ->select('start_time','end_time')
        ->orderBy('start_time')
        ->get();
    if ($requests->count() > 0) {
        return response()->json([
            'status' => false,
            'message' => ' You can`t add a new Task at this time',
            'data' => $requests
        ], 400);
    }

 
    $newTask = Task::create([
        'service_id' => $request->service_id,
      //  'company_id' => Auth::guard('company_api')->id(),
        'name' => $request->name,
        'description'=> $request->description,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'status' => 'pending', 
        'worker_id'=>$request->worker_id,
        'worker_request_id'=>$request->worker_request_id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Request added successfully',
        'data' => $newTask
    ]);
}



public function getWorkerTaskes(Request $request)
    {
        $worker = Auth::guard('worker_api')->user();
        $requests = Task::join('services', 'task.service_id', 'services.id')
            ->with('company')
            ->where('services.user_id', $worker->id)
            ->when($request->service_id, fn ($query) => $query->where('task.service_id', $request->service_id))
            ->select(['task.*', 'services.name'])
            ->paginate(5);
        $services_req = TaskResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }









    
}







