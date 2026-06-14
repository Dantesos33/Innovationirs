<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaignRecipient extends Model
{
    protected $table = 'newsletter_campaign_recipients';

    protected $fillable = [
        'campaign_id', 'subscriber_id',
        'status', 'sent_at', 'opened_at', 'open_count',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'opened_at'  => 'datetime',
        'open_count' => 'integer',
    ];

    public function campaign()
    {
        return $this->belongsTo(NewsletterCampaign::class, 'campaign_id');
    }

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class, 'subscriber_id');
    }
}
