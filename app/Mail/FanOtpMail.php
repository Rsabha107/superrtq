<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FanOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $code)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your RTQ+ verification code',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.fan-otp',
            with: ['code' => $this->code],
        );
    }
}
