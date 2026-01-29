<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'book_code',
        'title',
        'slug',
        'ISBN',
        'publish_year',
        'edition',
        'languages',
        'total_copies',
        'available_copies',
        'cover_image',
        'status'
    ];

    protected $casts = [
        'languages'    => 'array',
        'publish_year' => 'integer'
    ];

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        return ("{$this->cover_image}") ? url()->to('/' . "{$this->cover_image}") : null;
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function publishers()
    {
        return $this->hasMany(Publisher::class);
    }
}
