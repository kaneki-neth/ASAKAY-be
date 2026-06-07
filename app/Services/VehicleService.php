<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VehicleService
{
    /**
     * Get paginated vehicles with filters and sorting.
     */
    public function getPaginatedVehicles(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Vehicle::query()->with(['creator', 'updater', 'vehicleType']);

        // Search by name and code
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if (!empty($filters['vehicle_type_id'])) {
            $query->where('vehicle_type_id', $filters['vehicle_type_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sorting
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $allowedSortFields = ['name', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new vehicle.
     */
    public function createVehicle(array $data, int $userId): Vehicle
    {
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        return Vehicle::create($data);
    }

    /**
     * Update an existing vehicle.
     */
    public function updateVehicle(Vehicle $vehicle, array $data, int $userId): Vehicle
    {
        $data['updated_by'] = $userId;
        $vehicle->update($data);

        return $vehicle;
    }

    /**
     * Delete a vehicle (soft delete).
     */
    public function deleteVehicle(Vehicle $vehicle): bool
    {
        return $vehicle->delete();
    }
}
