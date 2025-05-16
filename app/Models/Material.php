<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'file',
        'link',
        'img',
        'layout',
        'type',
        'read_counter',
        'download_counter',
        'created_by'
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke tabel options untuk layout
    public function layoutOption()
    {
        return $this->belongsTo(Option::class, 'layout');
    }
}