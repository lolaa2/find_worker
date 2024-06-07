<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchedulingRequestResource;
use App\Http\Resources\Api\ServiceRequestResource;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceRequestController extends Controller
{


    public function getCustomerRequests(Request $request)
    {
        $customer = Auth::guard('customer_api')->user();
        $requests = ServiceRequest::join('services', 'services_requests.service_id', 'services.id')
            ->where('services_requests.customer_id', $customer->id)
            ->select(['services_requests.*', 'services.name'])
            ->paginate(5);
        $services_req = ServiceRequestResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }

    public function getWorkerRequests(Request $request)
    {
        $worker = Auth::guard('worker_api')->user();
        $requests = ServiceRequest::join('services', 'services_requests.service_id', 'services.id')
            ->with('customer')
            ->where('services.user_id', $worker->id)
            ->when($request->service_id, fn ($query) => $query->where('services_requests.service_id', $request->service_id))
            ->select(['services_requests.*', 'services.name'])
            ->paginate(5);
        $services_req = ServiceRequestResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }
    public function getCompanyRequests(Request $requests)
    {
        $company = Auth::guard('company_api')->user();
        $requests = ServiceRequest::join('services', 'services_requests.service_id', 'services.id')
            ->with('customer')
            ->where('services.user_id', $company->id)
            ->when($requests->service_id, fn ($query) => $query
                ->where('services_requests.service_id', $requests->service_id))
            ->select(['services_requests.*', 'services.name'])
            ->paginate(5);
        $services_req = ServiceRequestResource::collection($requests);
        return response()->json([
            'lastPage' => $requests->lastPage(),
            'currentPage' => $requests->currentPage(),
            'data' => $services_req,
        ]);
    }

public function storeRequest(Request $request)
{
    $validator = Validator::make($request->all(), [
        'note' => ['required', 'string'],
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

    $requests = ServiceRequest::where('service_id', $request->service_id)
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
            'message' => ' You can`t add a new Request at this time',
            'data' => $requests
        ], 400);
    }

 
    $newServiceRequest = ServiceRequest::create([
        'service_id' => $request->service_id,
        'customer_id' => Auth::guard('customer_api')->id(),
        'note' => $request->note,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'status' => 'pending' 
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Request added successfully',
        'data' => $newServiceRequest
    ]);
}


    public function accept(Request $request)
    {
        $serviceRequest = ServiceRequest::whereServiceId($request->service_id)
                            ->whereId($request->request_id)
                            ->firstOrFail();

        if($serviceRequest->status == 'pending')
        {
            $serviceRequest->status = 'accepted';
            $serviceRequest->save();

            return response()->json([
                'message' => 'Request accepted successfully  !'
            ]);
        }

        return response()->json([
            'message' => 'Request must be pending !'
        ],403);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'rate' => ['required' , 'integer' , 'min:1' , 'max:5']
        ]);
        $serviceRequest = ServiceRequest::whereServiceId($request->service_id)
                            ->whereId($request->request_id)
                            ->firstOrFail();


        if($serviceRequest->status == 'accepted')
        {
            $serviceRequest->status = 'completed';
            $serviceRequest->rate = $request->rate;
            
            $serviceRequest->save();

            return response()->json([
                'message' => 'Request completed successfully  !'
            ]);
        }

        return response()->json([
            'message' => 'Request must be pending !'
        ],403);
    }

    public function cancel(Request $request){
        $serviceRequest = ServiceRequest::whereServiceId($request->service_id)
        ->whereId($request->request_id)
        ->firstOrFail();

if($serviceRequest->status == 'pending')
{
$serviceRequest->status = 'canceled';
$serviceRequest->save();

return response()->json([
'message' => 'Request canceled successfully  !'
]);
}

return response()->json([
'message' => 'Request must be pending !'
],403);
    }
    ////////////////////ola

    public function getAcceptedRequestsTimes(Request $request, $service_id)
{ dd($request);
    $acceptedRequests = ServiceRequest::where('status', 'accepted')->where('service_id', $service_id)->get();
   
    $times = [];
    foreach ($acceptedRequests as $request) {
    
        $times[] = $request->available_times;
    }

    return response()->json([
        'service_id' => $service_id,
    ], 200);
}//ola
    public function schedulingRequest(Request $request)
    {
       $worker=Auth::guard('worker_api') ;
        $filteredRequests = ServiceRequest::join('services', 'services_requests.service_id', 'services.id')
            ->where('status', 'accepted')
            ->select('services_requests.start_time', 'services_requests.end_time', 'services.name','services_requests.*')
             ->orderBy('start_time')
              ->paginate(10);


        $servic_req = SchedulingRequestResource::collection($filteredRequests);

        return response()->json([
            'data' => $servic_req,
        ]);
    }
    

    

}



