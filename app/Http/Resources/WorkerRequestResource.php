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
            'companyName'=>$this->company->name,
            'workerName' => $this->user_name,
            'workerEmail' => $this->user_email,
            'workerPhone' => $this->user_phone,
            'workerId' => $this->worker_id,
            'skils' => $this->skils,
            'status' => $this->status,
            'note'=>$this->note,
             

        ];
    }
}
