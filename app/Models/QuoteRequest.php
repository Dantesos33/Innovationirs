<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $table = 'quote_requests';

    // ── All real DB columns confirmed from seeder ──────────────────────────
    // make and model are plain TEXT columns (not FK integers).
    // year, oem_part_number, condition, urgency do NOT exist as columns —
    // they are merged into model/notes in the controller before saving.
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'company',
        'make',  // plain text, e.g. "Caterpillar"
        'model', // plain text, e.g. "2018 320D"
        'serial_number',
        'part_number',
        'part_description',
        'quantity',
        'notes',
        'admin_notes',
        'assigned_to',
        'status',
        'ip_address',
        'referrer_url',
        'utm_source', 'utm_medium', 'utm_campaign',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function replies()
    {
        return $this->hasMany(QuoteReply::class, 'quote_id')
            ->orderBy('created_at');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['new', 'open', 'in_progress']);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ── Accessors ─────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'new'         => 'badge-new',
            'open'        => 'badge-open',
            'in_progress' => 'badge-in-progress',
            'quoted'      => 'badge-quoted',
            'closed_won'  => 'badge-success',
            'closed_lost' => 'badge-closed',
            'spam'        => 'badge-danger',
            default       => 'badge-secondary',
        };
    }

    public function getIsNewAttribute(): bool
    {
        return $this->status === 'new';
    }
}
