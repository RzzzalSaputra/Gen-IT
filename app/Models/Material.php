<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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