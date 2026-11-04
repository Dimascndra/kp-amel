<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Complaint $complaint)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'layanan@bankmajalengka.co.id'),
                config('mail.from.name', 'Layanan Nasabah Bank Majalengka')
            ),
            subject: "[{$this->complaint->ticket_code}] Pengaduan Nasabah Diterima - Bank Majalengka",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_submitted',
        );
    }
}
