<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public string $type = 'new' // 'new' or 'status_update'
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->type === 'new'
            ? '[Bunda PAUD] Pengaduan Baru: ' . ($this->complaint->subject ?? 'Tanpa Subjek')
            : '[Bunda PAUD] Update Status Pengaduan Anda';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint-notification',
            with: [
                'complaint' => $this->complaint,
                'type' => $this->type,
            ]
        );
    }
}
