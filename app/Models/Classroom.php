<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
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
        'name',
        'code',
        'description',
        'create_by',
        'create_at',
        'update_at',
        'delete_at'
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
     * Get the user that created the classroom (teacher).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    
    /**
     * Get the members for the classroom.
     */
    public function members(): HasMany
    {
        return $this->hasMany(ClassroomMember::class);
    }
    
    /**
     * Get the students for the classroom.
     * Returns users who are members of this classroom with the student role.
     */
    public function students()
    {
        return $this->hasManyThrough(
            User::class,
            ClassroomMember::class,
            'classroom_id', // Foreign key on ClassroomMember table
            'id', // Foreign key on User table
            'id', // Local key on Classroom table
            'user_id' // Local key on ClassroomMember table
        )->where('classroom_members.role', 'student');
    }
    
    /**
     * Get the materials for the classroom.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(ClassroomMaterial::class);
    }
    
    /**
     * Get the assignments for the classroom.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ClassroomAssignment::class);
    }
    
    /**
     * Scope a query to only include non-deleted classrooms.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('delete_at');
    }
}