<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassroomAssignment extends Model
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
        'description',
        'due_date',
        'file',
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
        'due_date' => 'datetime',
        'create_at' => 'datetime',
        'update_at' => 'datetime',
        'delete_at' => 'datetime',
    ];
    
    /**
     * Get the classroom that the assignment belongs to.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
    
    /**
     * Get the user that created the assignment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    
    /**
     * Get the submissions for the assignment.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ClassroomSubmission::class, 'assignment_id');
    }
    
    /**
     * Scope a query to only include non-deleted assignments.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('delete_at');
    }
}