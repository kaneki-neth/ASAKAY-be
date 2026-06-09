<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'vehicle_type_id',
        'color',
        'status',
        'polyline',
        'created_by',
    ];

    protected $casts = [
        'polyline' => 'array',
    ];

    /**
     * Get the stops associated with the route.
     */
    public function stops()
    {
        return $this->belongsToMany(Stop::class)
            ->withPivot('order')
            ->orderBy('route_stop.order')
            ->withTimestamps();
    }

    /**
     * Get the vehicle type.
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Get the user who created the route.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
