<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'img',
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'read_counter' => 'integer',
    ];

    /**
     * Get jobs associated with this company.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}