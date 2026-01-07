<?php

namespace App\Mail;

use App\Models\MitraPaud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MitraRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MitraPaud $mitra,
        public string $type = 'new' // 'new', 'approved', 'rejected'
    ) {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'new' => '[Bunda PAUD] Pendaftaran Mitra Baru: ' . $this->mitra->nama_organisasi,
            'approved' => '[Bunda PAUD] Pendaftaran Mitra Anda Disetujui',
            'rejected' => '[Bunda PAUD] Pendaftaran Mitra Anda Ditolak',
        ];

        return new Envelope(subject: $subjects[$this->type] ?? $subjects['new']);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mitra-notification',
            with: [
                'mitra' => $this->mitra,
                'type' => $this->type,
            ]
        );
    }
}
