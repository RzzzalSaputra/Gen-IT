<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirment',
        'salary_range',
        'register_link',
        'type',
        'experience',
        'work_type', 
        'read_counter',
    ];

    /**
     * Get the company that owns this job.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the option that represents the type of this job.
     */
    public function jobType()
    {
        return $this->belongsTo(Option::class, 'type');
    }

    /**
     * Get the option that represents the experience required for this job.
     */
    public function experienceLevel()
    {
        return $this->belongsTo(Option::class, 'experience');
    }

    /**
     * Get the option that represents the work type of this job.
     */
    public function workType()
    {
        return $this->belongsTo(Option::class, 'work_type');
    }
}