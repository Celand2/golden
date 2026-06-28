<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'referral_code',
        'referred_by',
        'role',
        'wallet_balance',
        'referral_count',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_balance' => 'decimal:2',
        'referral_count' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                do {
                    $user->referral_code = Str::upper(Str::random(8));
                } while (self::where('referral_code', $user->referral_code)->exists());
            }
        });
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(self::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'referred_by');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function activeInvestments(): HasMany
    {
        return $this->investments()->where('status', 'active');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function referralCommissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function dailyClaims(): HasMany
    {
        return $this->hasMany(DailyClaim::class);
    }

    public function getReferralLinkAttribute(): string
    {
        return url('/register?ref='.$this->referral_code);
    }

    public function getReferralUpline(int $levels = 3): array
    {
        $upline = [];
        $current = $this;

        for ($level = 1; $level <= $levels; $level++) {
            if (! $current->referrer) {
                break;
            }

            $current = $current->referrer;
            $upline[$level] = $current;
        }

        return $upline;
    }
}
