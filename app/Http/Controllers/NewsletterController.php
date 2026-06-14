<?php
namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): mixed
    {
        // Rate limit
        $key = 'newsletter.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Too many attempts.'], 429);
            }
            return back()->withErrors(['email' => 'Too many attempts. Please try again later.']);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name'  => 'nullable|string|max:150',
        ]);

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'name'              => $validated['name'] ?? null,
                'is_active'         => true,
                'unsubscribe_token' => Str::random(40),
                'subscribed_at'     => now(),
                'unsubscribed_at'   => null,
            ]
        );

        RateLimiter::hit($key, 300);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You\'ve been subscribed to our newsletter!',
            ]);
        }

        return back()->with('success', 'You\'ve been subscribed to our newsletter!');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        $subscriber->update([
            'is_active'       => false,
            'unsubscribed_at' => now(),
        ]);

        return view('pages.unsubscribed', compact('subscriber'));
    }
}
