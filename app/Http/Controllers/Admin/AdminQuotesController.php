<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\QuoteReplyMail;
use App\Models\Admin;
use App\Models\QuoteReply;
use App\Models\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminQuotesController extends Controller
{
    public function __construct(
        protected QuoteService $quoteService
    ) {}

    public function index(Request $request)
    {
        $query = QuoteRequest::with('assignedTo')->latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('part_description', 'like', "%{$term}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $quotes = $query->paginate(25)->withQueryString();
        $admins = Admin::active()->get();
        $counts = $this->quoteService->getStatusCounts();

        return view('admin.quotes.index', compact('quotes', 'admins', 'counts'));
    }

    public function show(QuoteRequest $quote)
    {
        $quote->load(['replies.admin', 'assignedTo']);

        if ($quote->status === 'new') {
            $quote->update(['status' => 'open']);
        }

        $admins = Admin::active()->get();

        return view('admin.quotes.show', compact('quote', 'admins'));
    }

    public function update(Request $request, QuoteRequest $quote)
    {
        $request->validate([
            'status'      => 'required|in:new,open,in_progress,quoted,closed_won,closed_lost,spam',
            'assigned_to' => 'nullable|exists:admins,id',
            'admin_notes' => 'nullable|string',
        ]);

        $quote->update($request->only('status', 'assigned_to', 'admin_notes'));

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'Quote updated successfully.');
    }

    public function updateStatus(Request $request, QuoteRequest $quote)
    {
        $request->validate(['status' => 'required|string']);
        $quote->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function reply(Request $request, QuoteRequest $quote)
    {
        $request->validate([
            'message'    => 'required|string',
            'send_email' => 'boolean',
        ]);

        $reply = QuoteReply::create([
            'quote_id' => $quote->id,
            'admin_id' => Auth::guard('admin')->id(),
            'message'  => $request->message,
            'is_admin' => true,
        ]);

        if ($request->boolean('send_email', true)) {
            try {
                Mail::to($quote->email)->send(new QuoteReplyMail($quote, $reply));
                $reply->update(['email_sent' => true, 'email_sent_at' => now()]);
            } catch (\Exception $e) {
                \Log::error('Quote reply email failed: ' . $e->getMessage());
            }
        }

        if (in_array($quote->status, ['new', 'open'])) {
            $quote->update(['status' => 'in_progress']);
        }

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'Reply sent successfully.');
    }

    public function destroy(QuoteRequest $quote)
    {
        $quote->replies()->delete();
        $quote->delete();

        return redirect()
            ->route('admin.quotes.index')
            ->with('success', 'Quote deleted.');
    }

    public function export(Request $request)
    {
        $quotes = QuoteRequest::when($request->status, fn($q) => $q->byStatus($request->status))
            ->latest()
            ->lazy(500);

        return response()->streamDownload(function () use ($quotes) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Name', 'Email', 'Phone', 'Company',
                'Make', 'Model', 'Part Description', 'Status', 'Submitted',
            ]);

            foreach ($quotes as $quote) {
                fputcsv($handle, [
                    $quote->id,
                    $quote->full_name,
                    $quote->email,
                    $quote->phone,
                    $quote->company,
                    $quote->make,
                    $quote->model,
                    $quote->part_description,
                    $quote->status,
                    $quote->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'quotes-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
