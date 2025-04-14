<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomSubmission extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'user_id',
        'content',
        'file',
        'submitted_at',
        'graded',
        'grade',
        'feedback', 
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'graded' => 'boolean',
    ];
    
    /**
     * Get the assignment that the submission belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ClassroomAssignment::class, 'assignment_id');
    }
    
    /**
     * Get the user that submitted the assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}