<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookIssue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'book_id',
        'member_id',
        'librarian_id',
        'issue_date',
        'due_date',
        'return_date',
        'status'
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'return_date' => 'date'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
