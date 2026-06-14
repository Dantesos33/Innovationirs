<?php
namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingService
{
    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    /**
     * Save multiple settings from admin form submission.
     */
    public function saveMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }
        // Flush all setting caches
        Cache::flush();
    }

    /**
     * Get all settings grouped for admin display.
     */
    public function getAllGrouped(): array
    {
        return SiteSetting::orderBy('group_name')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group_name')
            ->toArray();
    }
}
