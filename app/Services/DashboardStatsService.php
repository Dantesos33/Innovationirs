<?php
namespace App\Services;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Part;
use App\Models\QuoteRequest;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    /**
     * Top-level stat cards for dashboard.
     */
    public function getStats(): array
    {
        return [
            'total_parts'    => Part::count(),
            'active_parts'   => Part::where('status', 'active')->count(),
            'total_quotes'   => QuoteRequest::count(),
            'new_quotes'     => QuoteRequest::where('status', 'new')->count(),
            'total_contacts' => ContactMessage::count(),
            'new_contacts'   => ContactMessage::where('status', 'new')->count(),
            'subscribers'    => NewsletterSubscriber::where('is_active', true)->count(),
            'blog_posts'     => BlogPost::where('status', 'published')->count(),
            'testimonials'   => Testimonial::where('is_active', true)->count(),
        ];
    }

    /**
     * Last 30 days labels for Chart.js.
     */
    public function getChartLabels(): array
    {
        return collect(range(29, 0))->map(function ($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('M d');
        })->toArray();
    }

    /**
     * Quote requests per day for the last 30 days.
     */
    public function getChartData(): array
    {
        $data = QuoteRequest::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return collect(range(29, 0))->map(function ($daysAgo) use ($data) {
            $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');
            return $data[$date] ?? 0;
        })->toArray();
    }

    /**
     * Quote status breakdown for pie chart.
     */
    public function getQuoteStatusBreakdown(): array
    {
        return QuoteRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Parts count per category for bar chart.
     */
    public function getPartsByCategoryData(): array
    {
        return Category::withCount(['parts' => fn($q) => $q->where('status', 'active')])
            ->orderByDesc('parts_count')
            ->take(10)
            ->get()
            ->map(fn($c) => ['label' => $c->name, 'count' => $c->parts_count])
            ->toArray();
    }

    /**
     * Recent quotes for dashboard table.
     */
    public function getRecentQuotes(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return QuoteRequest::with('assignedTo')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Recent contact messages for dashboard table.
     */
    public function getRecentContacts(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return ContactMessage::latest()->take($limit)->get();
    }

    /**
     * Unread quote count for nav badge.
     */
    public function getUnreadQuoteCount(): int
    {
        return QuoteRequest::where('status', 'new')->count();
    }
}
