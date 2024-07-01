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
            'name'=>$this->company->name,
            'workerName' => $this->user_name,
            'workerId' => $this->worker_id,
            'skils' => $this->skils,
            'status' => $this->status,
             

        ];
    }
}
