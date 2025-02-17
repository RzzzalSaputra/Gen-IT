<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'file',
        'link',
        'img',
        'type'
    ];

    public function option()
    {
        return $this->belongsTo(Option::class, 'type');
    }

    public function studies()
    {
        return $this->hasMany(Study::class);
    }
}