<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviousResource extends JsonResource
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
          //  'user_name' => $this->users->name,
            'describtion' => $this->description,
            'title' => $this->title,
            'images' => ImagesResource::collection($this->images)


        ];
    }
}
