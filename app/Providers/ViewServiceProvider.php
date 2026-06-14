<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use App\Models\Category;
use App\Models\EquipmentType;
use App\Models\Make;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ── Share nav data with all public-facing views ────────────────────
        View::composer([
            'layouts.app', 'partials.*', 'pages.*',
            'parts.*', 'blog.*', 'makes.*',
            'categories.*', 'equipment-types.*',
        ], function ($view) {
            $navMakes = Cache::remember('nav.makes', 3600, fn() =>
                Make::active()
                    ->withCount(['parts' => fn($q) => $q->active()])
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
            );

            $navCategories = Cache::remember('nav.categories', 3600, fn() =>
                Category::active()
                    ->withCount(['parts' => fn($q) => $q->active()])
                    ->orderBy('sort_order')
                    ->take(12)
                    ->get()
            );

            $navEquipmentTypes = Cache::remember('nav.equipment_types', 3600, fn() =>
                EquipmentType::active()
                    ->orderBy('sort_order')
                    ->get()
            );

            $view->with(compact('navMakes', 'navCategories', 'navEquipmentTypes'));
        });

        // ── Share badge counts with admin layout ───────────────────────────
        // Exposes $unreadQuotes, $unreadContacts, $pendingApplications
        // directly so the sidebar blade can use them without array notation.
        View::composer('layouts.admin', function ($view) {
            try {
                $counts = Cache::remember('admin.sidebar_badges', 120, function () {
                    $unreadQuotes        = \App\Models\QuoteRequest::where('status', 'new')->count();
                    $unreadContacts      = \App\Models\ContactMessage::where('status', 'new')->count();
                    $pendingApplications = \App\Models\JobApplication::where('status', 'new')->count();

                    return compact('unreadQuotes', 'unreadContacts', 'pendingApplications');
                });

                $view->with($counts);
            } catch (\Throwable $e) {
                $view->with([
                    'unreadQuotes'        => 0,
                    'unreadContacts'      => 0,
                    'pendingApplications' => 0,
                ]);
            }
        });
    }
}
