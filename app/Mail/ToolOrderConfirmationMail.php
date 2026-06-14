<?php
namespace App\Mail;

use App\Models\ToolOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ToolOrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ToolOrder $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed — ' . $this->order->order_number . ' | ' . config('amsparts.company.name', 'Parts Plus Innovation Solutions'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tool-order-confirmation',
        );
    }
}
