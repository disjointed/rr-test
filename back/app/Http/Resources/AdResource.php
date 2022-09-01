<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray($request)
    {
        $photos = $this->photos;

        if (!empty($photos)) {
            $mainPhoto = $photos[0];
        } else {
            $mainPhoto = null;
        }

        $optionalFields = $request->get('fields', []);

        if (!is_array($optionalFields)) {
            $optionalFields = [];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'main_photo' => $mainPhoto,
            'description' => $this->when(in_array('description', $optionalFields), $this->description),
            'photos' => $this->when(in_array('photos', $optionalFields), $this->photos),
            'created_at' => $this->created_at->format('d.m.Y, H:i'),
        ];
    }
}
