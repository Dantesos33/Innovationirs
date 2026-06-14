<?php
namespace App\Services;

use App\Mail\NewContactNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function create(array $data, Request $request): ContactMessage
    {
        $message = ContactMessage::create(array_merge($data, [
            'ip_address'   => $request->ip(),
            'referrer_url' => $request->header('referer'),
            'status'       => 'new',
        ]));

        $adminEmail = config('amsparts.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewContactNotification($message));
            } catch (\Exception $e) {
                \Log::error('Contact notification email failed: ' . $e->getMessage());
            }
        }

        return $message;
    }

    public function getUnreadCount(): int
    {
        return ContactMessage::where('status', 'new')->count();
    }
}
