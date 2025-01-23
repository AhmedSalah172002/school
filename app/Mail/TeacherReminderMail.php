<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $teacherName , public $courseName){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Teacher Reminder Mail',
            from: new Address(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Teacher/teacher_reminder',
            with: [
                'teacherName' => $this->teacherName,
                "courseName" => $this->courseName,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
