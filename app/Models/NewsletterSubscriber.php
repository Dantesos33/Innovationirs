<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $table = 'newsletter_subscribers';

    protected $fillable = [
        'email', 'first_name', 'last_name',
        'is_active', 'unsubscribe_token',
        'subscribed_at', 'unsubscribed_at',
        'source', 'ip_address',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function campaignRecipients()
    {
        return $this->hasMany(NewsletterCampaignRecipient::class, 'subscriber_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function unsubscribe(): void
    {
        $this->update([
            'is_active'       => false,
            'unsubscribed_at' => now(),
        ]);
    }

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $sub) {
            if (empty($sub->unsubscribe_token)) {
                $sub->unsubscribe_token = Str::random(60);
            }
            if (empty($sub->subscribed_at)) {
                $sub->subscribed_at = now();
            }
        });
    }
}
