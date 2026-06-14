<?php
namespace App\Services;

use App\Mail\NewsletterCampaignMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignRecipient;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

class NewsletterService
{
    /**
     * Subscribe a new email, or reactivate if previously unsubscribed.
     */
    public function subscribe(
        string $email,
        array $extra = [],
        ?string $ip = null,
        string $source = 'website'
    ): NewsletterSubscriber {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if (! $subscriber->is_active) {
                $subscriber->update([
                    'is_active'       => true,
                    'unsubscribed_at' => null,
                    'subscribed_at'   => now(),
                    'source'          => $source,
                    'ip_address'      => $ip,
                ]);
            }
            return $subscriber;
        }

        return NewsletterSubscriber::create([
            'email'      => $email,
            'first_name' => $extra['first_name'] ?? null,
            'last_name'  => $extra['last_name'] ?? null,
            'source'     => $source,
            'ip_address' => $ip,
            'is_active'  => true,
        ]);
    }

    /**
     * Send a campaign to all active subscribers.
     * Each email is dispatched individually to the queue.
     */
    public function sendCampaign(NewsletterCampaign $campaign): void
    {
        $subscribers = NewsletterSubscriber::active()->get();
        $count       = $subscribers->count();

        $campaign->update([
            'status'          => 'sending',
            'recipient_count' => $count,
        ]);

        foreach ($subscribers as $subscriber) {
            // Create delivery record
            $recipient = NewsletterCampaignRecipient::create([
                'campaign_id'   => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'status'        => 'pending',
            ]);

            try {
                Mail::to($subscriber->email)
                    ->queue(new NewsletterCampaignMail($campaign, $subscriber));

                $recipient->update([
                    'status'  => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                $recipient->update(['status' => 'failed']);
                \Log::error("Newsletter send failed for {$subscriber->email}: " . $e->getMessage());
            }
        }

        $campaign->update([
            'status'          => 'sent',
            'sent_at'         => now(),
            'delivered_count' => NewsletterCampaignRecipient::where('campaign_id', $campaign->id)
                ->where('status', 'sent')
                ->count(),
        ]);
    }

    /**
     * Get total active subscriber count.
     */
    public function getActiveCount(): int
    {
        return NewsletterSubscriber::active()->count();
    }
}
