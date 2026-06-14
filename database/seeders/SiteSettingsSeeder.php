<?php
namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ── General ─────────────────────────────────────────────────────
            ['key' => 'site_name', 'value' => 'AMS Parts', 'type' => 'text', 'group_name' => 'general', 'label' => 'Site Name', 'is_public' => true, 'sort_order' => 1],
            ['key' => 'site_tagline', 'value' => 'Heavy Equipment Parts Specialists', 'type' => 'text', 'group_name' => 'general', 'label' => 'Site Tagline', 'is_public' => true, 'sort_order' => 2],
            ['key' => 'site_email', 'value' => 'parts@amsparts.com', 'type' => 'email', 'group_name' => 'general', 'label' => 'Contact Email', 'is_public' => true, 'sort_order' => 3],
            ['key' => 'site_phone', 'value' => '1-800-255-6253', 'type' => 'text', 'group_name' => 'general', 'label' => 'Phone Number', 'is_public' => true, 'sort_order' => 4],
            ['key' => 'site_address', 'value' => '2710 S. Main Street, Middletown, OH 45044', 'type' => 'text', 'group_name' => 'general', 'label' => 'Address', 'is_public' => true, 'sort_order' => 5],
            ['key' => 'site_hours', 'value' => 'Mon–Fri 8:00 AM – 5:30 PM EST', 'type' => 'text', 'group_name' => 'general', 'label' => 'Business Hours', 'is_public' => true, 'sort_order' => 6],

            // ── SEO ─────────────────────────────────────────────────────────
            ['key' => 'meta_title', 'value' => 'AMS Parts | Heavy Equipment Replacement Parts', 'type' => 'text', 'group_name' => 'seo', 'label' => 'Default Meta Title', 'is_public' => false, 'sort_order' => 1],
            ['key' => 'meta_description', 'value' => 'AMS Parts supplies new, used, and rebuilt heavy equipment parts for Caterpillar, Komatsu, John Deere, and more. Fast shipping worldwide.', 'type' => 'textarea', 'group_name' => 'seo', 'label' => 'Default Meta Description', 'is_public' => false, 'sort_order' => 2],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'text', 'group_name' => 'seo', 'label' => 'Google Analytics ID', 'is_public' => false, 'sort_order' => 3],
            ['key' => 'google_tag_manager', 'value' => '', 'type' => 'text', 'group_name' => 'seo', 'label' => 'Google Tag Manager ID', 'is_public' => false, 'sort_order' => 4],

            // ── Social ───────────────────────────────────────────────────────
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/amsparts', 'type' => 'url', 'group_name' => 'social', 'label' => 'Facebook URL', 'is_public' => true, 'sort_order' => 1],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'url', 'group_name' => 'social', 'label' => 'Twitter / X URL', 'is_public' => true, 'sort_order' => 2],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/amsparts', 'type' => 'url', 'group_name' => 'social', 'label' => 'LinkedIn URL', 'is_public' => true, 'sort_order' => 3],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'url', 'group_name' => 'social', 'label' => 'YouTube URL', 'is_public' => true, 'sort_order' => 4],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'url', 'group_name' => 'social', 'label' => 'Instagram URL', 'is_public' => true, 'sort_order' => 5],

            // ── Shipping ─────────────────────────────────────────────────────
            ['key' => 'shipping_free_over', 'value' => '500', 'type' => 'text', 'group_name' => 'shipping', 'label' => 'Free Shipping Over ($)', 'is_public' => true, 'sort_order' => 1],
            ['key' => 'shipping_processing_days', 'value' => '1-2', 'type' => 'text', 'group_name' => 'shipping', 'label' => 'Processing Days', 'is_public' => true, 'sort_order' => 2],
            ['key' => 'shipping_domestic_days', 'value' => '3-7', 'type' => 'text', 'group_name' => 'shipping', 'label' => 'Domestic Transit Days', 'is_public' => true, 'sort_order' => 3],
            ['key' => 'shipping_international', 'value' => '1', 'type' => 'boolean', 'group_name' => 'shipping', 'label' => 'International Shipping', 'is_public' => true, 'sort_order' => 4],

            // ── Notifications ────────────────────────────────────────────────
            ['key' => 'notify_new_quote', 'value' => '1', 'type' => 'boolean', 'group_name' => 'notifications', 'label' => 'Email on New Quote', 'is_public' => false, 'sort_order' => 1],
            ['key' => 'notify_new_contact', 'value' => '1', 'type' => 'boolean', 'group_name' => 'notifications', 'label' => 'Email on New Contact', 'is_public' => false, 'sort_order' => 2],
            ['key' => 'notify_email_override', 'value' => '', 'type' => 'email', 'group_name' => 'notifications', 'label' => 'Override Notification Email', 'is_public' => false, 'sort_order' => 3],

            // ── Appearance ──────────────────────────────────────────────────
            ['key' => 'announcement_bar_text', 'value' => 'Free shipping on orders over $500 | Call 1-800-255-6253 for same-day quotes', 'type' => 'text', 'group_name' => 'appearance', 'label' => 'Announcement Bar Text', 'is_public' => true, 'sort_order' => 1],
            ['key' => 'announcement_bar_active', 'value' => '1', 'type' => 'boolean', 'group_name' => 'appearance', 'label' => 'Show Announcement Bar', 'is_public' => true, 'sort_order' => 2],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group_name' => 'appearance', 'label' => 'Maintenance Mode', 'is_public' => false, 'sort_order' => 3],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
