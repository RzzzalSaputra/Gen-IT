<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'img',
        'type',
        'gmap',
        'province',
        'city',
        'address',
        'website',
        'instagram',
        'facebook',
        'x',
        'read_counter',
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->img && Storage::disk('public')->exists($model->img)) {
                Storage::disk('public')->delete($model->img);
            }
        });
    }

    public function typeOption()
    {
        return $this->belongsTo(Option::class, 'type');
    }

    public function studies()
    {
        return $this->hasMany(Study::class);
    }
}