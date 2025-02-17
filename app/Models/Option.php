<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'value'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'layout');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'option');
    }
}