<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    protected $table = 'newsletter_campaigns';

    protected $fillable = [
        'admin_id', 'subject', 'preview_text',
        'body_html', 'body_text', 'status',
        'scheduled_at', 'sent_at',
        'recipient_count', 'delivered_count', 'open_count', 'click_count',
    ];

    protected $casts = [
        'scheduled_at'    => 'datetime',
        'sent_at'         => 'datetime',
        'recipient_count' => 'integer',
        'delivered_count' => 'integer',
        'open_count'      => 'integer',
        'click_count'     => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function recipients()
    {
        return $this->hasMany(NewsletterCampaignRecipient::class, 'campaign_id');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->delivered_count === 0) {
            return 0.0;
        }

        return round(($this->open_count / $this->delivered_count) * 100, 1);
    }
}
