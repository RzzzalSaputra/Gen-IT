<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'reference_id',
        'reference_type',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}