<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'file',
        'link',
        'created_by'
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->file && Storage::disk('public')->exists($model->file)) {
                Storage::disk('public')->delete($model->file);
            }
        });
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option');
    }
}