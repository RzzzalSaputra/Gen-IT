<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Vicon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'desc',
        'img',
        'time',
        'link',
        'download',
        'created_by'
    ];

    protected $casts = [
        'time' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            // Delete img if exists
            if ($model->img && Storage::disk('public')->exists($model->img)) {
                Storage::disk('public')->delete($model->img);
            }
            
            // Delete download file if exists
            if ($model->download && Storage::disk('public')->exists($model->download)) {
                Storage::disk('public')->delete($model->download);
            }
        });
    }

    // Define relationship with User model
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}