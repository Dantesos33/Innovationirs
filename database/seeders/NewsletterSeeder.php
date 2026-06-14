<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignRecipient;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsletterSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        // ── Subscribers ────────────────────────────────────────────────────
        $subscribers = [
            ['email' => 'jharrington@harringtonexcavating.com', 'first_name' => 'James', 'last_name' => 'Harrington', 'source' => 'quote_form'],
            ['email' => 'lisa@drummondgrading.com', 'first_name' => 'Lisa', 'last_name' => 'Drummond', 'source' => 'website'],
            ['email' => 'rcastellano@castellanodemo.com', 'first_name' => 'Robert', 'last_name' => 'Castellano', 'source' => 'website'],
            ['email' => 'angela.morrow@morrowconstruction.net', 'first_name' => 'Angela', 'last_name' => 'Morrow', 'source' => 'footer'],
            ['email' => 'tbergstrom@bergstromsite.com', 'first_name' => 'Tony', 'last_name' => 'Bergstrom', 'source' => 'footer'],
            ['email' => 'svo@vo-groundworks.com', 'first_name' => 'Sandra', 'last_name' => 'Vo', 'source' => 'website'],
            ['email' => 'jennifer@forsytheinf.com', 'first_name' => 'Jennifer', 'last_name' => 'Forsythe', 'source' => 'website'],
            ['email' => 'phil@eckertpaving.com', 'first_name' => 'Phil', 'last_name' => 'Eckert', 'source' => 'direct'],
            ['email' => 'randy@kowalskiexc.com', 'first_name' => 'Randy', 'last_name' => 'Kowalski', 'source' => 'direct'],
            ['email' => 'marcus@williamsheavy.net', 'first_name' => 'Marcus', 'last_name' => 'Williams', 'source' => 'footer'],
            ['email' => 'tanya@brecklandclearing.com', 'first_name' => 'Tanya', 'last_name' => 'Breckenridge', 'source' => 'website'],
            ['email' => 'doug@midwestgrading.com', 'first_name' => 'Doug', 'last_name' => 'Pfeiffer', 'source' => 'footer'],
            ['email' => 'steve.nakamura@pcdemo.com', 'first_name' => 'Steve', 'last_name' => 'Nakamura', 'source' => 'website'],
            ['email' => 'carl.hutchinson@hutchinsontrenching.com', 'first_name' => 'Carl', 'last_name' => 'Hutchinson', 'source' => 'footer'],
            ['email' => 'parts.buyer@nationalfleet.net', 'first_name' => 'Tom', 'last_name' => 'Bradshaw', 'source' => 'website'],
            // One unsubscribed
            ['email' => 'unsubscribed.user@example.com', 'first_name' => 'Test', 'last_name' => 'Unsubscribe', 'source' => 'website', 'is_active' => false],
        ];

        $created = [];
        foreach ($subscribers as $sub) {
            $isActive = $sub['is_active'] ?? true;
            unset($sub['is_active']);

            $created[] = NewsletterSubscriber::updateOrCreate(
                ['email' => $sub['email']],
                array_merge($sub, [
                    'is_active'         => $isActive,
                    'unsubscribe_token' => Str::random(60),
                    'subscribed_at'     => Carbon::now()->subDays(rand(1, 90)),
                    'unsubscribed_at'   => $isActive ? null : Carbon::now()->subDays(5),
                    'ip_address'        => '192.168.1.' . rand(1, 254),
                ])
            );
        }

        // ── Past Campaign ──────────────────────────────────────────────────
        if ($admin) {
            $activeSubscribers = collect($created)->filter(fn($s) => $s->is_active);

            $campaign = NewsletterCampaign::updateOrCreate(
                ['subject' => 'Now In Stock: Rebuilt CAT 320D and Komatsu PC200-8 Hydraulic Pumps'],
                [
                    'admin_id'        => $admin->id,
                    'subject'         => 'Now In Stock: Rebuilt CAT 320D and Komatsu PC200-8 Hydraulic Pumps',
                    'preview_text'    => 'These go fast — freshly rebuilt, tested to 3,000 PSI, 1-year warranty.',
                    'body_html'       => '<h1>Fresh Stock Alert</h1><p>We just completed a new batch of rebuilt hydraulic main pumps for the CAT 320D and Komatsu PC200-8. Each pump is tested on our hydraulic test stand before shipping.</p><p><strong>CAT 320D Main Pump (P/N 259-0814)</strong> — In stock now. Was $3,200 — now $2,950.</p><p><strong>Komatsu PC200-8 Boom Cylinder Seal Kit (P/N 707-98-45230)</strong> — 25 units in stock. $95 each.</p><p>Call 1-800-255-6253 or reply to this email to order.</p>',
                    'body_text'       => 'We just completed a new batch of rebuilt hydraulic main pumps for the CAT 320D and Komatsu PC200-8. Call 1-800-255-6253 or reply to order.',
                    'status'          => 'sent',
                    'scheduled_at'    => Carbon::now()->subDays(20),
                    'sent_at'         => Carbon::now()->subDays(20),
                    'recipient_count' => $activeSubscribers->count(),
                    'delivered_count' => $activeSubscribers->count(),
                    'open_count'      => (int) round($activeSubscribers->count() * 0.38),
                    'click_count'     => (int) round($activeSubscribers->count() * 0.12),
                ]
            );

            // Create recipient records
            foreach ($activeSubscribers as $subscriber) {
                NewsletterCampaignRecipient::updateOrCreate(
                    [
                        'campaign_id'   => $campaign->id,
                        'subscriber_id' => $subscriber->id,
                    ],
                    [
                        'status'  => 'sent',
                        'sent_at' => Carbon::now()->subDays(20),
                    ]
                );
            }

            // Draft campaign
            NewsletterCampaign::updateOrCreate(
                ['subject' => 'Winter Maintenance Guide: Keep Your Fleet Running in Cold Weather'],
                [
                    'admin_id'     => $admin->id,
                    'subject'      => 'Winter Maintenance Guide: Keep Your Fleet Running in Cold Weather',
                    'preview_text' => 'Cold starts, hydraulic fluid viscosity, and battery maintenance — everything you need to know.',
                    'body_html'    => '<h1>Winter Maintenance Checklist</h1><p>Draft — content in progress.</p>',
                    'body_text'    => 'Draft — content in progress.',
                    'status'       => 'draft',
                ]
            );
        }
    }
}
