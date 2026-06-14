<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'last_login_at'     => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function quoteReplies()
    {
        return $this->hasMany(QuoteReply::class, 'admin_id');
    }

    public function contactReplies()
    {
        return $this->hasMany(ContactReply::class, 'admin_id');
    }

    public function assignedQuotes()
    {
        return $this->hasMany(QuoteRequest::class, 'assigned_to');
    }

    public function assignedContacts()
    {
        return $this->hasMany(ContactMessage::class, 'assigned_to');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'admin_id');
    }

    public function newsletterCampaigns()
    {
        return $this->hasMany(NewsletterCampaign::class, 'admin_id');
    }

    public function uploadedMedia()
    {
        return $this->hasMany(MediaLibrary::class, 'admin_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=FFD000&color=1a1a1a';
    }
}
