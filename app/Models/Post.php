<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted()
    {
        static::deleting(function ($model) {
            // Delete img if exists
            if ($model->img && Storage::disk('public')->exists($model->img)) {
                Storage::disk('public')->delete($model->img);
            }
            
            // Delete file if exists
            if ($model->file && Storage::disk('public')->exists($model->file)) {
                Storage::disk('public')->delete($model->file);
            }
        });
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'layout');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
