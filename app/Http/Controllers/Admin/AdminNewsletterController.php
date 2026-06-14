<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNewsletterController extends Controller
{
    public function __construct(protected NewsletterService $newsletterService)
    {}

    // ─── Subscribers ─────────────────────────────────────────────────────────

    public function subscribers(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subscribers = $query->paginate(30)->withQueryString();
        $totalActive = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.newsletter.subscribers', compact('subscribers', 'totalActive'));
    }

    public function exportSubscribers()
    {
        $subscribers = NewsletterSubscriber::where('is_active', true)->lazy(500);

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'First Name', 'Last Name', 'Source', 'Subscribed At']);
            foreach ($subscribers as $sub) {
                fputcsv($handle, [
                    $sub->email,
                    $sub->first_name,
                    $sub->last_name,
                    $sub->source,
                    $sub->subscribed_at?->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, 'subscribers-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function deleteSubscriber(int $id)
    {
        NewsletterSubscriber::findOrFail($id)->delete();
        return redirect()->route('admin.newsletter.subscribers')->with('success', 'Subscriber removed.');
    }

    // ─── Campaigns ────────────────────────────────────────────────────────────

    public function campaigns(Request $request)
    {
        $campaigns = NewsletterCampaign::with('creator')->latest()->paginate(20)->withQueryString();
        return view('admin.newsletter.campaigns', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.newsletter.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'      => 'required|string|max:300',
            'preview_text' => 'nullable|string|max:200',
            'body_html'    => 'required|string',
            'body_text'    => 'nullable|string',
            'status'       => 'required|in:draft,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $data['admin_id'] = Auth::guard('admin')->id();

        NewsletterCampaign::create($data);

        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaign created.');
    }

    public function show(NewsletterCampaign $campaign)
    {
        $campaign->load(['creator', 'recipients.subscriber']);
        return view('admin.newsletter.show', compact('campaign'));
    }

    public function send(Request $request, NewsletterCampaign $campaign)
    {
        if (! in_array($campaign->status, ['draft', 'scheduled'])) {
            return back()->with('error', 'Campaign has already been sent.');
        }

        $this->newsletterService->sendCampaign($campaign);

        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaign sent successfully.');
    }

    public function destroy(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Cannot delete a sent campaign.');
        }

        $campaign->recipients()->delete();
        $campaign->delete();

        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaign deleted.');
    }
}
