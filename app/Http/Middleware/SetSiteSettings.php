<?php
namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetSiteSettings
{
    public function handle(Request $request, Closure $next)
    {
        // Share site settings with all views
        $settings = SiteSetting::getPublic();
        View::share('siteSettings', $settings);

        return $next($request);
    }
}
