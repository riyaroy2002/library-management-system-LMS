<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'state',
        'code'
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function setCodeAttribute($value)
    {
        $this->attribute['code'] = strtoupper($value);
    }
}
