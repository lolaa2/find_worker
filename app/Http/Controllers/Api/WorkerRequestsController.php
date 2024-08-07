<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WorkerRequestResource;
use App\Models\WorkerRequest;
use App\Models\ServiceRequest;

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

    
    public function cancel(Request $request){
   $workerRequest = WorkerRequest::where('worker_id', $request->worker_id)
   ->where('id', $request->request_id)
                            ->firstOrFail();

if($workerRequest->status == 'pending')
{
$workerRequest->status = 'canceled';
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



    public function acceptRequest(Request $request)
{  $company = Auth::guard('company_api')->user();
    $workerRequest = WorkerRequest::where('company_id' , $company->id)->findOrFail($request->request_id);


    if (!$company) {
        return response()->json([
            'status' => false,
            'message' => 'Company not found',
        ], 404);
    }

    if (!$workerRequest->status=='accepted') {
        return response()->json([
            'status' => false,
            'message' => 'Must Be Accepted',
        ], 404);
    }




    $workerRequest->update([
        'status' => 'accepted'
    ]);


    return response()->json([
        'message' => 'Employee created successfully',
    ]);
}
public function fetchCompanyEmployee(Request $request)
{
    $company = Auth::guard('company_api')->user();
   $requests = WorkerRequest::join('users', 'worker_requests.worker_id','users.id')
  // ->with('user')
        ->where('worker_requests.company_id', $company->id)
        ->where('worker_requests.status','accepted')
        ->select('worker_requests.*', 'users.name as user_name','users.phone as user_phone','users.email as user_email')
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
    $requests = Task::join('services_requests','task.request_id','services_requests.id')->
    where('service_id', $request->service_id)
    ->where('worker_id', $request->worker_id)
    ->where('request_id', $request->request_id)
    ->where(function ($query) use ($request) {
        $query->whereBetween('task.start_time', [$request->start_time, $request->end_time])
              ->orWhereBetween('task.end_time', [$request->start_time, $request->end_time])
             ->orWhereBetween(DB::raw("timestamp('".$request->start_time."')"), ['task.start_time', 'task.end_time'])
            ->orWhereBetween(DB::raw("timestamp('".$request->end_time."')"), ['task.start_time', 'task.end_time']);
    })
    ->where('task.status', 'accepted') 
    ->select('task.start_time', 'task.end_time')
    ->orderBy('task.start_time')
    ->get();
    if ($requests->count() > 0) {
        return response()->json([
            'status' => false,
            'message' => ' You can`t add a new Task at this time',
            'data' => $requests
        ], 400);
    }
    $serviceRequest = ServiceRequest::find($request->request_id);
    if (!$serviceRequest) {
        return response()->json([
            'tatus' => false,
            'essage' => 'Invalid request ID',
        ], 400);
    }
 
    $newTask = Task::create([
        'service_id' => $request->service_id,
        'company_id' => Auth::guard('company_api')->id(),
        'name' => $request->name,
        'description'=> $request->description,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'status' => 'pending', 
        'worker_id'=>$request->worker_id,
        'request_id'=>$request->request_id,
       // 'worker_request_id'=>$request->worker_request_id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Request added successfully',
        'data' => $newTask
    ]);
}



public function getWorkerTaskes(Request $request)
    {
        $user = Auth::guard('worker_api')->user();
        $requests = Task::with('service.serviceable')
        -> with('servicesRequests')
            ->where('worker_id',$user->id)
            ->paginate(5);
        // dd($requests);
        $services_req = TaskResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }


    public function getCompanyTaskes(Request $request)
    {
        $user = Auth::guard('company_api')->user();
        $requests = Task::with('service.serviceable')
        -> with('servicesRequests')
            ->where('worker_id',$user->id)
            ->paginate(5);
        // dd($requests);
        $services_req = TaskResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }



    public function taskCancle(Request $request){
        $cancleT = Task::where('worker_id', $request->worker_id)
        ->where('id', $request->task_id)
                                 ->firstOrFail();
     
     if($cancleT->status == 'pending')
     {
     $cancleT->status = 'canceled';
     $cancleT->save();
     
     return response()->json([
     'message' => 'Request canceled successfully  !'
     ]);
     }
     
     return response()->json([
     'message' => 'Request must be pending !'
     ],403);
         }


         public function taskAccept(Request $request){
            $cancleT = Task::where('worker_id', $request->worker_id)
            ->where('id', $request->task_id)
                                     ->firstOrFail();
         
         if($cancleT->status == 'pending')
         {
         $cancleT->status = 'Accepted';
         $cancleT->save();
         
         return response()->json([
         'message' => 'Request Accepted Successfully  !'
         ]);
         }
         
         return response()->json([
         'message' => 'Request must be pending !'
         ],403);
             }

             public function taskComplet(Request $request){
                $cancleT = Task::where('worker_id', $request->worker_id)
                ->where('id', $request->task_id)
                                         ->firstOrFail();
             
             if($cancleT->status == 'accepted')
             {
             $cancleT->status = 'Completed';
             $cancleT->save();
             
             return response()->json([
             'message' => 'Request Completed Successfully  !'
             ]);
             }
             
             return response()->json([
             'message' => 'Request must be pending !'
             ],403);
                 }
    


    }
  








    








