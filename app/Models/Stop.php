<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'description',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the routes that pass through this stop.
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class)
            ->withPivot('order')
            ->withTimestamps();
    }

    /**
     * Get the user who created the stop.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
