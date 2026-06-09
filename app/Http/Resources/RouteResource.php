<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
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
            'vehicle_type' => new VehicleTypeResource($this->whenLoaded('vehicleType')),
            'color' => $this->color,
            'status' => $this->status,
            'polyline' => $this->polyline,
            'stops' => StopResource::collection($this->whenLoaded('stops')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
