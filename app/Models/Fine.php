<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'book_issue_id',
        'amount',
        'late_days',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
    ];


    public function bookIssue()
    {
        return $this->belongsTo(BookIssue::class);
    }
}
