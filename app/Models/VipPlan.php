<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_amount',
        'daily_rate',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'duration_days' => 'integer',
        'is_active' => 'boolean',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
