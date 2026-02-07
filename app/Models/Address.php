<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'address_line1',
        'address_line2',
        'city',
        'state_id',
        'district_id',
        'pincode',
        'country',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function librarians()
    {
        return $this->hasMany(Librarian::class);
    }

    public function publishers()
    {
        return $this->hasMany(Publisher::class);
    }
}
