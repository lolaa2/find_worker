<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use Carbon\Carbon;


use App\Http\Controllers\Controller;
use App\Http\Resources\SchedulingRequestResource;
use App\Http\Resources\Api\ServiceRequestResource;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceRequestController extends Controller
{


    public function getCustomerRequests(Request $request)
    {
        $customer = Auth::guard('customer_api')->user();
    
        if ($customer instanceof Customer) {
            $userType = $customer; 
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $requests = ServiceRequest::join('services', 'services_requests.serviceable_id', 'services.serviceable_id')
            ->where('services_requests.customer_id', $userType->id)
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
        $user = Auth::guard('worker_api')->user();

        if ($user instanceof Company) {
            $userType = $user; 
        } elseif ($user instanceof User) {
            $userType = $user; 
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $requests = ServiceRequest::join('services', 'services_requests.serviceable_id', 'services.id')
            ->with('customer')
            ->where('services.serviceable_id', $userType->id)
            ->when($request->serviceable_id, fn ($query) => $query->where('services_requests.serviceable_id', $request->serviceable_id))
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
        $requests = ServiceRequest::join('services', 'services_requests.serviceable_id', 'services.serviceable_id')
            ->with('customer')
            ->where('services.user_id', $company->id)
            ->when($requests->service_id, fn ($query) => $query
                ->where('services_requests.serviceable_id', $requests->serviceable_id))
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
        'serviceable_id' => ['required', 'integer'],
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

    $requests = ServiceRequest::where('serviceable_id', $request->serviceable_id)
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
        'serviceable_id' => $request->serviceable_id,
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
        $serviceRequest = ServiceRequest::whereServiceableId($request->serviceable_id)
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
        $serviceRequest = ServiceRequest::whereServiceableId($request->serviceable_id)
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
        $serviceRequest = ServiceRequest::whereServiceableId($request->serviceable_id)
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
    $acceptedRequests = ServiceRequest::where('status', 'accepted')
    ->where('service_id', $service_id)->get();
   
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
        $filteredRequests = ServiceRequest::join('services', 'services_requests.serviceable_id', 'services.serviceable_id')
            ->where('status', 'accepted')
            ->select('services_requests.start_time', 'services_requests.end_time', 'services.name','services_requests.*')
             ->orderBy('start_time')
              ->paginate(10);


        $servic_req = SchedulingRequestResource::collection($filteredRequests);

        return response()->json([
            'data' => $servic_req,
        ]);
    }



    // public function freeTime(){
    // $availableTime=ServiceRequest::join('services','services_requests.serviceable_id','services.serviceable_id')
    // ->whereNotIn('status', ['accepted'])
    // ->select('services_requests.start_time','services_requests.*')
    //          ->orderBy('start_time')
    //           ->paginate(10);

    //           return response()->json([
    //             'data' => $availableTime,
    //         ]);







    // لحساب وظيفة تعيين الاوقات المتاحة بناءً على جدول المواعيد للعامل، يمكنك استخدام الكود التالي في Laravel

    
    // function calculateAvailableTimes($worker_id, $date) {
    //     $workStart = new SupportCarbon("08:00:00");
    //     $workEnd = new SupportCarbon("17:00:00");
    
    //     $serviceRequests = ServiceRequest::where('worker_id', $worker_id)
    //         ->where('status', ['accepted'])
    //         ->orderBy('start_time')
    //         ->get();
    
    //     $availableTimes = [];
    
    //     foreach ($serviceRequests as $key => $request) {
    //         $endTime = new SupportCarbon($request->end_time);
    
    //         // Add available time slots between requests
    //         if ($key == 0) {
    //             $startTime = new SupportCarbon("08:00:00"); // Start of work
    //         } else {
    //             $prevEndTime = new SupportCarbon($serviceRequests[$key - 1]->end_time);
    //             $startTime = $prevEndTime;
    //         }
    
    //         $timeDiff = $startTime->diff($endTime);
    
    //         if ($timeDiff->h * 60 + $timeDiff->i >= 60) { // Check if available time slot is at least 1 hour
    //             $availableTimes[] = [
    //                 'start' => $startTime->format('H:i'),
    //                 'end' => $endTime->format('H:i')
    //             ];
    //         }
    //     }
    
    //     // Add available time slots after last request
    //     $lastEndTime = new SupportCarbon($serviceRequests->last()->end_time);
    //     $lastStartTime = $lastEndTime;
    
    //     $timeDiff = $lastStartTime->diff($workEnd);
    
    //     if ($timeDiff->h * 60 + $timeDiff->i >= 60) { // Check if available time slot is at least 1 hour
    //         $availableTimes[] = [
    //             'start' => $lastStartTime->format('H:i'),
    //             'end' => $workEnd->format('H:i')
    //         ];
    //     }
    
    //     return $availableTimes;

    
    // // هذا الكود يحسب الأوقات المتاحة بين المواعيد للعامل ويعود بجدول يحتوي على الاوقات المتاحة بين المواعيد وبعد آخر موعد، مع الحفاظ على المدة الزمنية المتاحة لكل فترة. يمكنك مشاهدة الاوقات المتاحة وعرضها للمستخدم بشكل مطلوب.
    
    // }


    function calculateAvailableTimes($serviceable_id) {
        $workStart = Carbon::parse("08:00:00");
        $workEnd = Carbon::parse("17:00:00");
    
        $serviceRequests = ServiceRequest::join('services', 'services_requests.serviceable_id', 'services.serviceable_id')
    //   -> where('serviceable_id', $serviceable_id)
            ->where('status', 'accepted')
            ->select('services_requests.start_time', 'services_requests.end_time', 'services.name','services_requests.*') // تأكد من أن القيمة 'accepted' صحيحة
            ->orderBy('start_time')
            ->get();
    
        $availableTimes = [];
    
        foreach ($serviceRequests as $key => $request) {      
              $endTime = Carbon::parse($request->end_time);
    
            // Add available time slots between requests
            if ($key == 0) {
                $startTime = Carbon::parse("08:00:00"); // Start of work
            } else {
                $prevEndTime = Carbon::parse($serviceRequests[$key - 1]->$request->end_time);
                $startTime = $prevEndTime;
            }
    
            $timeDiff = $startTime->diffInSeconds($endTime);
    
            if ($timeDiff >= 3600) { // Check if available time slot is at least 1 hour
                $availableTimes[] = [
                    'start' => $startTime->format('H:i'),
                    'end' => $endTime->format('H:i')
                ];
            }
        }
    
        // Add available time slots after last request
        $lastEndTime = Carbon::parse($serviceRequests->last()->end_time);
        $lastStartTime = $lastEndTime;
    
        $timeDiff = $lastStartTime->diffInSeconds($workEnd);
    
        if ($timeDiff >= 3600) { // Check if available time slot is at least 1 hour
            $availableTimes[] = [
                'start' => $lastStartTime->format('H:i'),
                'end' => $workEnd->format('H:i')
            ];
        }

    
        
              return response()->json([
                'data' => $availableTimes,
            ]);

    }
    
    
}


