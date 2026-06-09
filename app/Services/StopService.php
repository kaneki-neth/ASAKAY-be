<?php

namespace App\Services;

use App\Models\Stop;
use Illuminate\Support\Facades\Auth;

class StopService
{
    public function getPaginated($perPage = 15, $search = null)
    {
        $query = Stop::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['created_by'] = Auth::id();
        return Stop::create($data);
    }

    public function update(Stop $stop, array $data)
    {
        $stop->update($data);
        return $stop;
    }

    public function delete(Stop $stop)
    {
        // Check if stop is used in any active routes
        if ($stop->routes()->exists()) {
            throw new \Exception("Cannot delete stop because it is associated with active routes.");
        }

        return $stop->delete();
    }

    public function getAll()
    {
        return Stop::orderBy('name')->get();
    }
}
