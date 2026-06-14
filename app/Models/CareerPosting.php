<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerPosting extends Model
{
    protected $table = 'career_postings';

    protected $fillable = [
        'title', 'department', 'location', 'job_type',
        'description', 'requirements', 'benefits',
        'salary_range', 'apply_email',
        'is_active', 'posted_at', 'expires_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'posted_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'career_posting_id');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match ($this->job_type) {
            'full_time'  => 'Full-Time',
            'part_time'  => 'Part-Time',
            'contract'   => 'Contract',
            'internship' => 'Internship',
            'temporary'  => 'Temporary',
            default      => ucfirst($this->job_type),
        };
    }
}

// Add this method to the CareerPosting class — paste inside the class body
