<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'file',
        'img',
        'video_url',
        'layout',
        'created_by',
        'counter'
    ];

    public function option()
    {
        return $this->belongsTo(Option::class, 'layout');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
