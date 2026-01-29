<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'contact_no',
        'book_id',
        'address_id',
        'website',
        'logo',
        'description',
        'established_year',
        'status'
    ];


    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        return ("{$this->logo}") ? url()->to('/' . "{$this->logo}") : null;
    }
}
