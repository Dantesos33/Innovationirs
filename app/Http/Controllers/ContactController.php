<?php
namespace App\Http\Controllers;

use App\Mail\NewContactNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request): mixed
    {
        // Rate limit: 3 submissions per 10 minutes per IP
        $key = 'contact.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Too many submissions. Please try again in {$seconds} seconds.",
                ], 429);
            }
            return back()->withErrors(['email' => "Too many submissions. Please wait {$seconds} seconds."]);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'company' => 'nullable|string|max:150',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Split full name into first/last
        $nameParts = explode(' ', $validated['name'], 2);

        $data = [
            'first_name' => $nameParts[0],
            'last_name'  => $nameParts[1] ?? null,
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'company'    => $validated['company'] ?? null,
            'subject'    => $validated['subject'],
            'message'    => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status'     => 'new',
        ];

        $contact = ContactMessage::create($data);

        RateLimiter::hit($key, 600); // 10 min window

        // Admin notification
        try {
            Mail::to(config('amsparts.email_general'))
                ->send(new NewContactNotification($contact));
        } catch (\Throwable $e) {
            Log::error('Contact notification email failed', [
                'contact_id' => $contact->id,
                'error'      => $e->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent. We will get back to you within 24 hours.',
            ]);
        }

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent! We\'ll get back to you within 1 business day.');
    }
}
