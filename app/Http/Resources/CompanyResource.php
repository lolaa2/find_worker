<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'cityName' =>$this->city->name,
            'categoryName'=>$this->category->name,
            // 'rate' => (float) $this->requests_avg_rate,
            'phone' => $this->phone,
           'CreatAt' => $this->created_at->diffForHumans(),
           'services'=>$this->services,
           
        ];
    }
}
