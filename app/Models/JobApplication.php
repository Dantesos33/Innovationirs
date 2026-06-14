<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $fillable = [
        'career_posting_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'linkedin_url',
        'cover_letter',
        'cv_path',
        'cv_original_name',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function careerPosting()
    {
        return $this->belongsTo(CareerPosting::class, 'career_posting_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getCvUrlAttribute(): ?string
    {
        if (! $this->cv_path) {
            return null;
        }

        return asset('storage/' . $this->cv_path);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new'         => 'New',
            'reviewed'    => 'Reviewed',
            'shortlisted' => 'Shortlisted',
            'rejected'    => 'Rejected',
            'hired'       => 'Hired',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new'         => 'orange',
            'reviewed'    => 'blue',
            'shortlisted' => 'green',
            'rejected'    => 'red',
            'hired'       => 'green',
            default       => 'gray',
        };
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
