<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_details',
    ];

    protected $casts = [
        'contact_details' => 'array',
    ];

    public function leaseContracts(): HasMany
    {
        return $this->hasMany(LeaseContract::class);
    }

    public function inFlightOrders(): HasMany
    {
        return $this->hasMany(InFlightOrder::class, 'vendor_id');
    }
}
