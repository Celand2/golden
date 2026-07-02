<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'amount',
        'referral_count',
    ];

    protected $casts = [
        'level' => 'integer',
        'amount' => 'decimal:2',
        'referral_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}