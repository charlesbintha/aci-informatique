<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class QuoteRequestReceived extends Mailable
{
    public string $serviceName;

    public function __construct(public array $quote, public int $requestId)
    {
        $this->serviceName = config('aci.services.'.$quote['service'].'.title', 'Audit, conseil & formation');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->quote['email'])],
            subject: 'Nouvelle demande de devis #'.$this->requestId.' — '.$this->serviceName,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.quote-request', text: 'emails.quote-request-text');
    }
}
