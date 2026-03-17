<?php

namespace App\Mail;

use App\Domain\Orders\Entity\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderNotificationMail extends Mailable
{
    public function __construct(
        public readonly Order $order,
        public readonly string $notification_message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido #' . $this->order->getId() . ' recebido',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-notification',
        );
    }
}
