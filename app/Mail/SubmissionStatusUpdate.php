<?php

namespace App\Mail;

use App\Models\Submission; // <-- Import model Submission
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    // Properti publik agar bisa diakses di view email
    public Submission $submission;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(Submission $submission)
    {
        // Terima data submission saat Mailable ini dibuat
        $this->submission = $submission;
    }

    /**
     * Dapatkan "amplop" pesan.
     */
    public function envelope(): Envelope
    {
        // Atur subjek email secara dinamis
        $subject = 'Update Status Titipan: ' . $this->submission->product_name;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Dapatkan konten/isi pesan.
     */
    public function content(): Content
    {
        // Beri tahu Laravel untuk menggunakan file view ini sebagai isi email
        return new Content(
            view: 'emails.submission_status',
        );
    }

    /**
     * Dapatkan lampiran untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}