<?php

namespace App\Services;

use App\Models\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteService
{
    public function getPaginated($perPage = 15, $filters = [])
    {
        $query = Route::query()->with(['vehicleType', 'stops']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('code', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['vehicle_type_id'])) {
            $query->where('vehicle_type_id', $filters['vehicle_type_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();
            $stops = $data['stops'] ?? [];
            unset($data['stops']);

            $route = Route::create($data);

            if (!empty($stops)) {
                $this->syncStops($route, $stops);
            }

            return $route->load(['vehicleType', 'stops']);
        });
    }

    public function update(Route $route, array $data)
    {
        return DB::transaction(function () use ($route, $data) {
            $stops = $data['stops'] ?? null;
            unset($data['stops']);

            $route->update($data);

            if ($stops !== null) {
                $this->syncStops($route, $stops);
            }

            return $route->load(['vehicleType', 'stops']);
        });
    }

    protected function syncStops(Route $route, array $stops)
    {
        $syncData = [];
        foreach ($stops as $index => $stop) {
            $stopId = is_array($stop) ? $stop['id'] : $stop;
            $syncData[$stopId] = ['order' => $index];
        }
        $route->stops()->sync($syncData);
    }

    public function delete(Route $route)
    {
        return $route->delete();
    }
}
