<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class GestionNotification extends Notification
{
    use Queueable;

    public $gestion;
    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct($gestion,$ticket)
    {
        $this->gestion = $gestion;
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'gestion_id' => $this->gestion->id,
            'title' => 'Nueva gestion de ticket',
            'message' => 'Gestion del ticket: ' . $this->ticket->title,
            'url' => route('ticket.show', ['ticket' => $this->ticket->id]), // Aquí agregas la URL al ticket
            'coment' => $this->gestion->coment,
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
