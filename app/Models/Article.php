<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'summary',
        'status',
        'type',
        'writer',
        'post_time',
        'created_by',
    ];

    protected $dates = [
        'post_time',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function statusOption()
    {
        return $this->belongsTo(Option::class, 'status');
    }

    public function typeOption()
    {
        return $this->belongsTo(Option::class, 'type');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}