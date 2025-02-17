<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'respond_by',
        'created_by',
        'message',
        'respond_message',
        'status'
    ];

    public function responder()
    {
        return $this->belongsTo(User::class, 'respond_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}