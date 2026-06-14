<?php
namespace App\Services;

use App\Mail\NewQuoteNotification;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuoteService
{
    /**
     * Create a new quote request and send admin notification.
     */
    public function create(array $data, Request $request): QuoteRequest
    {
        $quote = QuoteRequest::create(array_merge($data, [
            'ip_address'   => $request->ip(),
            'referrer_url' => $request->header('referer'),
            'utm_source'   => $request->get('utm_source'),
            'utm_medium'   => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign'),
            'status'       => 'new',
            'quantity'     => $data['quantity'] ?? 1,
        ]));

        // Notify admin via email
        $adminEmail = config('amsparts.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewQuoteNotification($quote));
            } catch (\Exception $e) {
                \Log::error('Quote notification email failed: ' . $e->getMessage());
            }
        }

        return $quote;
    }

    /**
     * Get count of quotes by status for dashboard display.
     */
    public function getStatusCounts(): array
    {
        return QuoteRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get unread (new) quote count for nav badge.
     */
    public function getUnreadCount(): int
    {
        return QuoteRequest::where('status', 'new')->count();
    }
}
