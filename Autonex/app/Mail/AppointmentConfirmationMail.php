<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Időpont visszaigazolás – AutoNex',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.AppointmentConfirmationMail',
            with: [
                'userName' => $this->appointment->user->name,
                'date' => $this->appointment->date->format('Y. m. d.'),
                'time' => $this->appointment->time,
                'service' => $this->appointment->service,
                'car' => $this->appointment->car,
                'workNumber' => $this->appointment->work_number,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
