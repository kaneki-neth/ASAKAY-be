<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;    

class VehicleResource extends JsonResource
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
            'code' => $this->code,
            'vehicle_type_id' => $this->vehicle_type_id,
            'description' => $this->description,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'updater' => new UserResource($this->whenLoaded('updater')),
            'vehicle_type' => new VehicleTypeResource($this->whenLoaded('vehicleType')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

