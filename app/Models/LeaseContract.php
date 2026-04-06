<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaseContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'status',
        'start_date',
        'expected_return_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_return_date' => 'date',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function maneuvers(): HasMany
    {
        return $this->hasMany(Maneuver::class);
    }

    public function financialLedgers(): HasMany
    {
        return $this->hasMany(FinancialLedger::class);
    }
}
