<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;

class TelegramTicketNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $type;
    protected $oldStatus;

    public function __construct(Ticket $ticket, $type = 'created', $oldStatus = null)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $message = $this->buildMessage();
        
        try {
            $response = Http::post("https://api.telegram.org/bot" . config('services.telegram.bot_token') . "/sendMessage", [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            Log::info('Telegram notification sent', [
                'ticket_id' => $this->ticket->id,
                'type' => $this->type,
                'response' => $response->json()
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification', [
                'ticket_id' => $this->ticket->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    protected function buildMessage()
    {
        $ticket = $this->ticket;
        
        switch ($this->type) {
            case 'created':
                return $this->newTicketMessage();
            case 'status_changed':
                return $this->statusChangedMessage();
            default:
                return $this->newTicketMessage();
        }
    }

    protected function newTicketMessage()
    {
        $ticket = $this->ticket;
        $ap = $ticket->accessPoint;
        $location = "{$ap->room->floor->building->name} - {$ap->room->floor->display_name} - {$ap->room->name}";
        
        return " <b>TICKET BARU</b>\n\n" .
               " Nomor: <code>{$ticket->ticket_number}</code>\n" .
               " Access Point: <b>{$ap->name}</b>\n" .
               " Lokasi: {$location}\n" .
               " Kategori: {$ticket->category}\n" .
               " Pelapor: {$ticket->admin->name}\n" .
               " Unit: {$ticket->admin->unit_kerja}\n\n" .
               " Deskripsi:\n{$ticket->description}\n\n" .
               " Waktu: " . $ticket->created_at->format('d M Y H:i');
    }

    protected function statusChangedMessage()
    {
        $ticket = $this->ticket;
        
        $statusIcons = [
            'open' => '🔵',
            'in_progress' => '🟡',
            'resolved' => '🟢',
            'closed' => '⚫',
        ];
        
        $oldIcon = $statusIcons[$this->oldStatus] ?? '⚪';
        $newIcon = $statusIcons[$ticket->status] ?? '⚪';
        
        $message = " <b>STATUS TICKET DIUBAH</b>\n\n" .
                   " Nomor: <code>{$ticket->ticket_number}</code>\n" .
                   " Access Point: <b>{$ticket->accessPoint->name}</b>\n\n" .
                   "Status: {$oldIcon} <s>" . strtoupper($this->oldStatus) . "</s> → {$newIcon} <b>" . strtoupper($ticket->status) . "</b>\n\n";
        
        if ($ticket->status === 'resolved' && $ticket->resolution_notes) {
            $message .= " Catatan Penyelesaian:\n{$ticket->resolution_notes}\n\n";
            $message .= " Diselesaikan oleh: {$ticket->resolver->name}\n";
        }
        
        $message .= " Waktu: " . now()->format('d M Y H:i');
        
        return $message;
    }
}