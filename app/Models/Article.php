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
        'create_at',
        'create_by',
        'delete_at'
    ];

    protected $dates = [
        'post_time',
        'create_at',
        'delete_at',
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
        return $this->belongsTo(User::class, 'create_by');
    }
}