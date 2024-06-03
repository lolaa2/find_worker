<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'serviceId' => $this->service_id,
            'serviceName' => $this->name,
            'requestStatus' => $this->status,
            
            'startTime' => Carbon::parse($this->start_time)->toDateTimeString(),
            'endTime' => Carbon::parse($this->end_time)->toDateTimeString(),
            'note' => $this->note,
            'customer' => new AuthenticateableContactResource($this->whenLoaded('customer'))
              

        ];
    }
}
