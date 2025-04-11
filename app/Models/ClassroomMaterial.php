<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomMaterial extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'title',
        'content',
        'file',
        'link',
        'img',
        'type',
        'create_by',
        'create_at',
        'update_at',
        'delete_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'create_at' => 'datetime',
        'update_at' => 'datetime',
        'delete_at' => 'datetime',
    ];
    
    /**
     * Get the classroom that the material belongs to.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
    
    /**
     * Get the user that created the material.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    
    /**
     * Get the option type for this material.
     */
    public function typeOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'type');
    }
    
    /**
     * Scope a query to only include non-deleted materials.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('delete_at');
    }
}