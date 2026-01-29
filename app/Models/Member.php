<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_no',
        'alt_contact_no',
        'email',
        'user_id',
        'address_id',
        'gender',
        'date_of_birth',
        'member_code',
        'check_term'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
