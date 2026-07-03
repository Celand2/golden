<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LumicashAccount extends Model
{
    protected $table = 'lumicash_accounts';

    protected $fillable = [
        'phone',
        'name',
    ];
}
