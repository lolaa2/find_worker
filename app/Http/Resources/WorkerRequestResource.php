<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use App\Http\Resources\Api\AuthenticateableContactResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerRequestResource extends JsonResource
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
            'workerId' => $this->worker_id,
            'skils' => $this->skils,
            'requestStatus' => $this->status,
            'createdAt' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updatedAt' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'note' => $this->note,
            'worker' => new AuthenticateableContactResource($this->whenLoaded('worker'))
              

        ];
    }
}
