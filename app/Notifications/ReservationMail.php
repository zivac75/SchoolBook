<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $type = 'confirmation')
    {
        $this->reservation = $reservation;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = '';
        switch ($this->type) {
            case 'confirmation':
                $subject = 'Confirmation de votre réservation';
                break;
            case 'rappel':
                $subject = 'Rappel : votre rendez-vous approche';
                break;
            case 'annulation':
                $subject = 'Annulation de votre réservation';
                break;
        }
        return $this->subject($subject)
            ->markdown('emails.reservation')
            ->with([
                'reservation' => $this->reservation,
                'type' => $this->type,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reservation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
