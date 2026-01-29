<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'join_date',
        'duration_days',
        'expiry_date',
        'fee',
        'status',
        'member_id'
    ];

    protected $casts = [
        'join_date'   => 'date',
        'expiry_date' => 'date',
        'fee'         => 'decimal:2'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
