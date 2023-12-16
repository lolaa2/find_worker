<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'title'=>$this->name,
            'description'=>$this->description,
            'cityName'=>$this->city->name,
            'categoryName'=>$this->category->name,
            'price'=>$this->price." SP",
            'userName'=>$this->user->name,
            'userId'=>$this->user->id,
            'publishDate'=>$this->created_at->diffForHumans(),
            'images' => ImagesResource::collection($this->images)
            
         ];
    }
}
