<?php

namespace App\Services;

use App\Models\VehicleType;
use Illuminate\Support\Facades\Auth;

class VehicleTypeService
{
    /**
     * Get paginated vehicle types.
     */
    public function getPaginated($perPage = 10, $search = null)
    {
        $query = VehicleType::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->withCount(['vehicles', 'routes'])->latest()->paginate($perPage);
    }

    /**
     * Get all active vehicle types for options.
     */
    public function getOptions()
    {
        return VehicleType::where('status', 'active')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Create a new vehicle type.
     */
    public function create(array $data)
    {
        $data['created_by'] = Auth::id();
        return VehicleType::create($data);
    }

    /**
     * Update an existing vehicle type.
     */
    public function update(VehicleType $vehicleType, array $data)
    {
        $vehicleType->update($data);
        return $vehicleType;
    }

    /**
     * Delete a vehicle type.
     */
    public function delete(VehicleType $vehicleType)
    {
        if ($vehicleType->vehicles()->exists()) {
            throw new \Exception("This vehicle type cannot be deleted because it is currently assigned to existing vehicles.");
        }

        $vehicleType->delete();
        return true;
    }
}

