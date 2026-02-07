<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_no',
        'alt_contact_no',
        'email',
        'profile_image',
        'gender',
        'slug',
        'bio',
        'status'
    ];

    protected $appends = ['image_url', 'full_name'];
    public function getImageUrlAttribute()
    {
        return ("{$this->profile_image}") ? url()->to('/' . "{$this->profile_image}") : null;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book');
    }
}
