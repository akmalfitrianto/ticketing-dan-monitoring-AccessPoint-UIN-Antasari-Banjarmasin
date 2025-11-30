<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $oldStatus;
    public $newStatus;

    public function __construct(Ticket $ticket, string $oldStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $ticket->status;
    }

    public function envelope(): Envelope
    {
        $emoji = match ($this->newStatus) {
            'in_progress' => '🔄',
            'resolved' => '✅',
            'closed' => '🔒',
            default => '📋',
        };

        return new Envelope(
            subject: "{$emoji} Status Ticket #{$this->ticket->ticket_number} Diubah - {$this->ticket->category}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-status-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
