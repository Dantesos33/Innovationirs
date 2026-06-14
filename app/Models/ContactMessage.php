<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'company',
        'subject', 'message', 'admin_notes', 'assigned_to',
        'status', 'ip_address', 'referrer_url',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function replies()
    {
        return $this->hasMany(ContactReply::class, 'contact_id')
            ->orderBy('created_at');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
