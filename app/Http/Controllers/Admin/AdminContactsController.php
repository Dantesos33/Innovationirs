<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminContactsController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::with('assignedTo')->latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $contacts = $query->paginate(25)->withQueryString();
        $admins   = Admin::active()->get();
        $counts   = [
            'new'         => ContactMessage::where('status', 'new')->count(),
            'open'        => ContactMessage::where('status', 'open')->count(),
            'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
            'closed'      => ContactMessage::where('status', 'closed')->count(),
            'spam'        => ContactMessage::where('status', 'spam')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'admins', 'counts'));
    }

    public function show(ContactMessage $contact)
    {
        $contact->load(['replies.admin', 'assignedTo']);

        if ($contact->status === 'new') {
            $contact->update(['status' => 'open']);
        }

        $admins = Admin::active()->get();

        return view('admin.contacts.show', compact('contact', 'admins'));
    }

    public function update(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'status'      => 'required|string',
            'assigned_to' => 'nullable|exists:admins,id',
            'admin_notes' => 'nullable|string',
        ]);

        $contact->update($request->only('status', 'assigned_to', 'admin_notes'));

        return redirect()->route('admin.contacts.show', $contact)->with('success', 'Contact updated.');
    }

    public function updateStatus(Request $request, ContactMessage $contact)
    {
        $request->validate(['status' => 'required|string']);
        $contact->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function reply(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'message'    => 'required|string',
            'send_email' => 'boolean',
        ]);

        $reply = ContactReply::create([
            'contact_id' => $contact->id,
            'admin_id'   => Auth::guard('admin')->id(),
            'message'    => $request->message,
            'is_admin'   => true,
        ]);

        if ($request->boolean('send_email', true)) {
            try {
                Mail::to($contact->email)->send(new ContactReplyMail($contact, $reply));
                $reply->update(['email_sent' => true, 'email_sent_at' => now()]);
            } catch (\Exception $e) {
                \Log::error('Contact reply email failed: ' . $e->getMessage());
            }
        }

        if (in_array($contact->status, ['new', 'open'])) {
            $contact->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.contacts.show', $contact)->with('success', 'Reply sent.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->replies()->delete();
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted.');
    }
}
