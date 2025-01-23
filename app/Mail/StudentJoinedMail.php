<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentJoinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $studentName , public $courseName){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Joined To Course',
            from: new Address(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Student/student_joined',
            with: [
                'studentName ' => $this->studentName ,
                "courseName" => $this->courseName,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
