<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'total_stock',
        'available_stock',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function maneuvers(): HasMany
    {
        return $this->hasMany(Maneuver::class);
    }

    public function inFlightOrders(): HasMany
    {
        return $this->hasMany(InFlightOrder::class);
    }
}
