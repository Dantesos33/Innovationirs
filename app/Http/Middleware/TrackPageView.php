<?php
namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests for HTML responses
        if ($request->isMethod('GET')
            && ! $request->ajax()
            && ! str_starts_with($request->path(), 'admin')
            && ! str_starts_with($request->path(), 'api')
        ) {
            try {
                PageView::create([
                    'url'        => $request->path(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer'    => $request->header('referer'),
                    'session_id' => $request->session()->getId(),
                ]);
            } catch (\Exception $e) {
                // Fail silently — never break user experience for analytics
            }
        }

        return $response;
    }
}
