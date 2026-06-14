<?php
namespace App\Mail;

use App\Models\ToolOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ToolOrderAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ToolOrder $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 New Tool Order — ' . $this->order->order_number . ' ($' . number_format($this->order->total, 2) . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tool-order-admin-notification',
        );
    }
}
