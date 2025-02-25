<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'phone',
        'first_name',
        'last_name',
        'birthdate',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    // Relationships
    public function contactResponses()
    {
        return $this->hasMany(Contact::class, 'respond_by');
    }

    public function contactCreated()
    {
        return $this->hasMany(Contact::class, 'created_by');
    }

    // Scope for admin users
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function roleOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'role', 'id');
    }
}