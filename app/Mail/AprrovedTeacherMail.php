<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AprrovedTeacherMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $username){}

    public function envelope(): Envelope
    {

        return new Envelope(
            subject: 'Aprroved Teacher Mail',
            from: new Address(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Teacher/teacher_activated',
            with: [
                'username' => $this->username,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
