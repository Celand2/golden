<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'interest_rate',
        'duration_months',
        'total_repayment',
        'amount_repaid',
        'status',
        'approved_at',
        'repaid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_repayment' => 'decimal:2',
        'amount_repaid' => 'decimal:2',
        'approved_at' => 'datetime',
        'repaid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}