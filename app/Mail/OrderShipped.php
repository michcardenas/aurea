<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class OrderShipped extends Mailable
{
    public function __construct(
        public Order $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tu pedido #{$this->order->id} va en camino — nuvion glass",
            replyTo: [
                new Address(config('mail.contacto'), 'Nuvion Glass'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
        );
    }
}
