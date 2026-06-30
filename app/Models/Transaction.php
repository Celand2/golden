<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'phone',
        'provider',
        'note',
        'payment_proof',
        'proof_path',
        'recipient_phone',
        'recipient_name',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referralCommission(): BelongsTo
    {
        return $this->hasOne(ReferralCommission::class);
    }
}
