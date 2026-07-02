<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vip_plan_id',
        'amount',
        'daily_gain',
        'accumulated_gains',
        'total_claimed',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_gain' => 'decimal:2',
        'accumulated_gains' => 'decimal:2',
        'total_claimed' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vipPlan(): BelongsTo
    {
        return $this->belongsTo(VipPlan::class);
    }

    public function dailyClaims(): HasMany
    {
        return $this->hasMany(DailyClaim::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expires_at->isPast();
    }

    public function isClaimable(): bool
    {
        return $this->status === 'active' && $this->accumulated_gains > 0;
    }

    public function markExpired(): void
    {
        $this->status = 'expired';
        $this->save();
    }

    public function accrueDailyGain(): void
    {
        $this->accumulated_gains = $this->accumulated_gains + $this->daily_gain;
        $this->save();
    }
}
