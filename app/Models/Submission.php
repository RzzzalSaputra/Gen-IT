<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'file',
        'link',
        'img',
        'type',
        'status',
        'read_counter',
        'download_counter',
        'approve_at',
        'approve_by',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approve_at' => 'datetime', // Add this line to ensure approve_at is a Carbon instance
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

    // Relasi ke tabel Option (type)
    public function typeOption()
    {
        return $this->belongsTo(Option::class, 'type');
    }

    // Relasi ke tabel Option (status)
    public function statusOption()
    {
        return $this->belongsTo(Option::class, 'status');
    }

    // Relasi ke tabel User (yang menyetujui)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approve_by');
    }

    // Relasi ke tabel User (yang membuat)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
