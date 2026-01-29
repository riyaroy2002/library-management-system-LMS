<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cms extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'text_content',
        'image',
        'status',
        'extra'
    ];

    protected $casts = [
        'extra' => 'array'
    ];

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        return ("{$this->image}") ? url()->to('/' . "{$this->image}") : null;
    }

}
