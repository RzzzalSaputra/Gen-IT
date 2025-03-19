<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
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

    // Implementing the required method from FilamentUser interface
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    public function getNameAttribute()
    {
        return $this->user_name;
    }


    // Relationships
    public function contactResponses(): HasMany
    {
        return $this->hasMany(Contact::class, 'respond_by');
    }

    public function contactCreated(): HasMany
    {
        return $this->hasMany(Contact::class, 'created_by');
    }

    public function roleOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'role', 'id')->withDefault();
    }

    // Scope for admin users
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}