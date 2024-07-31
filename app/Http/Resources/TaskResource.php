<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        return[
            'id'=>$this->id,
            'name' => $this->name,
            'description'=>$this->description,
            'status'=>$this->status,
            'worker_request_id'=>$this->worker_request_id,
            'startTime' => Carbon::parse($this->start_time)->toDateTimeString(),
            'endTime' => Carbon::parse($this->end_time)->toDateTimeString(),
            'service' => $this->service,
            'Requests'=>$this->servicesRequests,
        
            ];
    }
}
