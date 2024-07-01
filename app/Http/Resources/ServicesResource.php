<?php

namespace App\Http\Resources;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
class ServicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        
            'id'=>$this->id,
            //'companyId'=>$this->companies->id,
            'title'=>$this->name,
            'description'=>$this->description,
            'cityName'=>$this->city->name,
            'categoryName'=>$this->category->name,
            'rate'=>(float) $this->requests_avg_rate,
            'price'=>$this->price,
            'userName'=>$this->serviceable?->name,
            'userId'=>$this->serviceable?->id,
            'userType' => Str::afterLast($this->serviceable_type,'\\'),
            'publishDate'=>$this->created_at->diffForHumans(),
            'images' => ImagesResource::collection($this->images)
            
         ];   
    }
}
