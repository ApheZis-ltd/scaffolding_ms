<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maneuver extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_contract_id',
        'equipment_id',
        'quantity',
        'type',
        'manager_id',
        'manager_comments',
    ];

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function leaseContract(): BelongsTo
    {
        return $this->belongsTo(LeaseContract::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
