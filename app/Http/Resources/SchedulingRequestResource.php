<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchedulingRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
        'serviceName' => $this->name,
        'startTime' => Carbon::parse($this->start_time)->toDateTimeString(),
        'endTime' => Carbon::parse($this->end_time)->toDateTimeString(),
        ];
 }

}
