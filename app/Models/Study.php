<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Study extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'duration',
        'link',
        'img',
        'level',
        'read_counter',
    ];

    /**
     * Get the school that owns this study program.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the option that represents the level of this study.
     */
    public function levelOption()
    {
        return $this->belongsTo(Option::class, 'level');
    }
}