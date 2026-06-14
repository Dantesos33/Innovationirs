<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role = 'staff')
    {
        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin panel.');
        }

        $admin = Auth::guard('admin')->user();

        if (! $admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated.');
        }

        // Role gate: only super_admin can access super_admin-only routes
        if ($role === 'super_admin' && ! $admin->isSuperAdmin()) {
            abort(403, 'Insufficient permissions.');
        }

        return $next($request);
    }
}
