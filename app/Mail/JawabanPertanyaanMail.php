<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JawabanPertanyaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pertanyaan;

    /**
     * Create a new message instance.
     */
    public function __construct($pertanyaan)
    {
        $this->pertanyaan = $pertanyaan;
    }

    /**
     * Subject email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jawaban Pertanyaan PPDB MAN Jeneponto'
        );
    }

    /**
     * Isi email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.jawaban-pertanyaan'
        );
    }

    /**
     * Attachments (opsional)
     */
    public function attachments(): array
    {
        return [];
    }
}